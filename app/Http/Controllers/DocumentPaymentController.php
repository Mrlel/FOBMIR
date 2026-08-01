<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use FedaPay\FedaPay;
use FedaPay\Transaction;
use FedaPay\Webhook;
use FedaPay\Error\SignatureVerification;

class DocumentPaymentController extends Controller
{
    private function bootFedaPay(): void
    {
        FedaPay::setApiKey(config('services.fedapay.secret_key'));
        FedaPay::setEnvironment(config('services.fedapay.environment', 'sandbox'));
    }

    /**
     * Page d'achat : formulaire nom / email / téléphone
     */
    public function buy($documentId)
    {
        $document = Document::findOrFail($documentId);
        return view('documents.individus_independant.buy', compact('document'));
    }

    /**
     * Initier le paiement : crée le DocumentPayment et redirige vers checkout
     */
    public function initiate(Request $request, $documentId)
    {
        $request->validate([
            'email'     => 'required|email',
            'telephone' => 'required|string',
            'nom'       => 'required|string',
        ]);

        $document = Document::findOrFail($documentId);

        // Déjà payé par cet email ?
        $existing = DocumentPayment::where('document_id', $document->id)
            ->where('email_acheteur', $request->email)
            ->where('statut', 'paye')
            ->first();

        if ($existing) {
            return redirect()->route('documents.download.page', $existing->token_telechargement)
                ->with('success', 'Vous avez déjà acheté ce document.');
        }

        $payment = DocumentPayment::create([
            'document_id'          => $document->id,
            'email_acheteur'       => $request->email,
            'telephone_acheteur'   => $request->telephone,
            'nom_acheteur'         => $request->nom,
            'statut'               => 'en_attente',
            'token_telechargement' => Str::random(64),
        ]);

        return redirect()->route('payment.checkout', $payment->id);
    }

    /**
     * Page de paiement FedaPay (JS checkout)
     */
    public function checkout($paymentId)
    {
        $payment = DocumentPayment::with('document')->findOrFail($paymentId);

        if ($payment->statut === 'paye') {
            return redirect()->route('documents.download.page', $payment->token_telechargement);
        }

        return view('documents.individus_independant.fedaPay', compact('payment'));
    }

    /**
     * Vérification après retour depuis FedaPay
     */
    public function verify(Request $request)
    {
        $transactionId = $request->query('transaction_id');
        $paymentId     = $request->query('payment_id');

        if (!$transactionId || !$paymentId) {
            return redirect()->route('mes.classeurs')->with('error', 'Informations de paiement manquantes.');
        }

        try {
            $this->bootFedaPay();

            $payment = DocumentPayment::findOrFail($paymentId);

            if ($payment->statut === 'paye') {
                return redirect()->route('documents.download.page', $payment->token_telechargement);
            }

            $transaction = Transaction::retrieve($transactionId);

            if ($transaction->status === 'approved') {
                $payment->update([
                    'statut'         => 'paye',
                    'transaction_id' => $transactionId,
                    'paye_le'        => now(),
                ]);

                return redirect()->route('documents.download.page', $payment->token_telechargement)
                    ->with('success', 'Paiement réussi ! Vous pouvez télécharger votre document.');
            }

            $payment->update(['statut' => 'echoue']);
            return redirect()->route('payment.failed', $payment->id);

        } catch (\Exception $e) {
            Log::error('FedaPay verify error: ' . $e->getMessage());
            return redirect()->route('mes.classeurs')->with('error', 'Erreur lors de la vérification du paiement.');
        }
    }

    /**
     * Page de téléchargement (après paiement confirmé)
     */
    public function downloadPage($token)
    {
        $payment = DocumentPayment::where('token_telechargement', $token)
            ->where('statut', 'paye')
            ->with('document')
            ->firstOrFail();

        return view('documents.individus_independant.download', compact('payment'));
    }

    /**
     * Téléchargement effectif du fichier
     */
    public function downloadFile($token)
    {
        $payment = DocumentPayment::where('token_telechargement', $token)
            ->where('statut', 'paye')
            ->with('document')
            ->firstOrFail();

        $document = $payment->document;

        if (!$document->fichier || !Storage::disk('public')->exists($document->fichier)) {
            abort(404, 'Fichier non trouvé.');
        }

        return Storage::disk('public')->download($document->fichier, $document->nom_fichier ?? basename($document->fichier));
    }

    /**
     * Page d'échec de paiement
     */
    public function failed($paymentId)
    {
        $payment = DocumentPayment::find($paymentId);
        return view('documents.individus_independant.failed', compact('payment'));
    }

    /**
     * Webhook FedaPay (POST)
     *
     * La requête n'est ni authentifiée ni protégée par CSRF (nécessaire pour un
     * webhook externe) : sa signature doit donc impérativement être vérifiée
     * avant de faire confiance à son contenu, sans quoi n'importe qui pourrait
     * forger une notification "transaction.approved" et obtenir un document
     * sans payer. Cf. FedaPay\Webhook::constructEvent (secret distinct de la
     * clé API, configuré sur le tableau de bord FedaPay puis dans .env).
     */
    public function webhook(Request $request)
    {
        $secret = config('services.fedapay.webhook_secret');
        $signature = $request->header('X-FEDAPAY-SIGNATURE');

        if (!$secret) {
            Log::error('FedaPay webhook reçu mais FEDAPAY_WEBHOOK_SECRET n\'est pas configuré : requête rejetée.');
            return response()->json(['status' => 'webhook not configured'], 503);
        }

        try {
            $event = Webhook::constructEvent($request->getContent(), $signature, $secret);
        } catch (\UnexpectedValueException|SignatureVerification $e) {
            Log::warning('FedaPay webhook rejeté : signature invalide.', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'invalid signature'], 400);
        }

        $payload       = json_decode(json_encode($event), true);
        $eventName     = $payload['name'] ?? null;
        $transactionId = $payload['entity']['id'] ?? null;
        $metadata      = $payload['entity']['custom_metadata'] ?? [];
        $paymentId     = $metadata['payment_id'] ?? null;

        if ($eventName === 'transaction.approved' && $transactionId && $paymentId) {
            $payment = DocumentPayment::find($paymentId);

            if ($payment && $payment->statut !== 'paye') {
                // Défense en profondeur : on revérifie le statut auprès de l'API
                // FedaPay plutôt que de faire confiance uniquement au contenu de l'événement.
                $this->bootFedaPay();
                $transaction = Transaction::retrieve($transactionId);

                if ($transaction->status === 'approved') {
                    $payment->update([
                        'statut'         => 'paye',
                        'transaction_id' => $transactionId,
                        'paye_le'        => now(),
                    ]);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
