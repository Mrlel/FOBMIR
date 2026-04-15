<?php

namespace App\Http\Controllers\IndependantPerson;

use App\Http\Controllers\Controller;
use App\Models\Classeur;
use App\Models\Document;
use App\Models\DocumentPayment;
use App\Models\TypeDocument;
use App\Services\CinetPayClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class documentController extends Controller
{
    public function create(Classeur $classeur)
    {
        $individu = Auth::guard('individu')->user();
        if (!$individu) {
            return redirect()->route('auto-enregistrement.login');
        }

        $dossier = $individu->dossier;
        if (!$dossier || $classeur->dossier_id !== $dossier->id) {
            return redirect()->route('individu.classeurs.index')
                ->with('error', 'Classeur non trouve.');
        }

        $typeDocuments = TypeDocument::orderBy('libelle')->get();

        return view('documents.individus_independant.create', compact('individu', 'dossier', 'classeur', 'typeDocuments'));
    }

    public function store(Request $request, Classeur $classeur)
    {
        $individu = Auth::guard('individu')->user();
        if (!$individu) {
            return redirect()->route('auto-enregistrement.login');
        }

        $dossier = $individu->dossier;
        if (!$dossier || $classeur->dossier_id !== $dossier->id) {
            return redirect()->route('individu.classeurs.index')
                ->with('error', 'Classeur non trouve.');
        }

        $request->validate([
            'libelle' => 'required|string|max:150',
            'numero' => 'nullable|string|max:25',
            'fichier' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'type_document_id' => 'required|exists:type_documents,id',
        ]);

        $data = $request->except('fichier');
        $data['classeur_id'] = $classeur->id;
        $data['date_ajout'] = now();

        if (Auth::check()) {
            $data['individu_id'] = Auth::id();
        }

        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['fichier'] = $file->storeAs('documents/individus_independants/' . $individu->id, $filename, 'public');
            $data['nom_fichier'] = $file->getClientOriginalName();
        }

        Document::create($data);

        return redirect()->route('individu.classeurs.show', $classeur)
            ->with('success', 'Document ajoute avec succes.');
    }

    public function download(Classeur $classeur, Document $document)
    {
        $individu = Auth::guard('individu')->user();
        if (!$individu) {
            return redirect()->route('auto-enregistrement.login');
        }

        $dossier = $individu->dossier;
        if (
            !$dossier ||
            $classeur->dossier_id !== $dossier->id ||
            $document->classeur_id !== $classeur->id
        ) {
            return redirect()->route('individu.classeurs.index')
                ->with('error', 'Document non trouve.');
        }

        if (!$document->fichier || !Storage::disk('public')->exists($document->fichier)) {
            return redirect()->back()->with('error', 'Fichier non trouve.');
        }

        // Télécharger payant: l'individu doit avoir un paiement "paid" pour ce document
        $alreadyPaid = DocumentPayment::paid()
            ->where('document_id', $document->id)
            ->where('payer_type', $individu::class)
            ->where('payer_id', $individu->id)
            ->exists();

        if (!$alreadyPaid) {
            // Réutiliser un paiement pending existant si présent
            $pending = DocumentPayment::where('document_id', $document->id)
                ->where('payer_type', $individu::class)
                ->where('payer_id', $individu->id)
                ->where('status', 'pending')
                ->latest('id')
                ->first();

            if ($pending && $pending->payment_url) {
                return redirect()->away($pending->payment_url);
            }

            $amount = (int) config('cinetpay.download_price', 200);
            $nomclient = trim(($individu->prenom ?? '') . ' ' . ($individu->nom ?? ''));
            $numeroSend = (string) ($individu->telephone ?? '');

            if (!config('cinetpay.api_key') || !config('cinetpay.api_password')) {
                return redirect()->back()->with('error', 'Paiement CinetPay non configuré (CINETPAY_API_KEY / CINETPAY_API_PASSWORD).');
            }

            $payment = DocumentPayment::create([
                'document_id' => $document->id,
                'payer_type' => $individu::class,
                'payer_id' => $individu->id,
                'provider' => 'cinetpay',
                'status' => 'pending',
                'amount' => $amount,
                'numero_send' => $numeroSend,
                'nomclient' => $nomclient ?: 'Client',
                'provider_payload' => [
                    'classeur_id' => $classeur->id,
                    'document_id' => $document->id,
                ],
            ]);

            $merchantTransactionId = 'd' . $payment->id;
            if (strlen($merchantTransactionId) > 30) {
                $merchantTransactionId = substr(hash('sha256', (string) $payment->id), 0, 30);
            }

            $payment->update([
                'provider_payload' => array_merge($payment->provider_payload ?? [], [
                    'merchant_transaction_id' => $merchantTransactionId,
                ]),
            ]);

            $email = filter_var($individu->email, FILTER_VALIDATE_EMAIL)
                ? $individu->email
                : (string) config('cinetpay.fallback_email');

            $firstName = $this->cinetPayClientName((string) ($individu->prenom ?? ''), 'Client');
            $lastName = $this->cinetPayClientName((string) ($individu->nom ?? ''), 'Individu');

            $paymentBody = [
                'currency' => (string) config('cinetpay.currency'),
                'merchant_transaction_id' => $merchantTransactionId,
                'amount' => $amount,
                'lang' => (string) config('cinetpay.lang'),
                'designation' => (string) config('cinetpay.designation'),
                'client_email' => $email,
                'client_first_name' => $firstName,
                'client_last_name' => $lastName,
                'success_url' => route('cinetpay.return', $payment),
                'failed_url' => route('cinetpay.failed', $payment),
                'notify_url' => route('cinetpay.notify'),
                'direct_pay' => false,
            ];

            if ($numeroSend !== '') {
                $paymentBody['client_phone_number'] = $numeroSend;
            }

            $cinetPay = app(CinetPayClient::class);
            $response = $cinetPay->initPayment($paymentBody);

            if (isset($response['_error'])) {
                $payment->delete();
                $msg = $response['_error'] === 'oauth_failed'
                    ? 'Connexion à CinetPay refusée : vérifiez CINETPAY_API_KEY, CINETPAY_API_PASSWORD et CINETPAY_BASE_URL dans le fichier .env.'
                    : 'Erreur lors de la connexion au service de paiement.';
                Log::warning('CinetPay init: erreur client', ['response' => $response]);

                return redirect()->back()->with('error', $msg);
            }

            $httpStatus = (int) ($response['_http_status'] ?? 500);
            $codeRaw = $response['code'] ?? null;
            $codeInt = is_numeric($codeRaw) ? (int) $codeRaw : null;
            $statusLine = strtoupper((string) ($response['status'] ?? ''));
            $hasPayment = !empty($response['payment_url']) && !empty($response['payment_token']);

            $initAccepted = $httpStatus < 400
                && $hasPayment
                && ($codeInt === 200 || $statusLine === 'OK');

            if (!$initAccepted) {
                $payment->delete();
                Log::warning('CinetPay init refusée', ['response' => $response]);

                return redirect()->back()->with('error', $this->cinetPayInitErrorMessage($response));
            }

            $details = $response['details'] ?? null;
            if (is_array($details) && strtoupper((string) ($details['status'] ?? '')) === 'FAILED') {
                $msg = is_string($details['message'] ?? null) ? $details['message'] : 'Paiement refusé.';
                $payment->delete();

                return redirect()->back()->with('error', $msg);
            }

            if (empty($response['payment_url']) || empty($response['payment_token'])) {
                return redirect()->back()->with('error', 'Impossible d’initier le paiement. Réessayez.');
            }

            $payment->refresh();

            $payment->update([
                'token' => (string) $response['payment_token'],
                'payment_url' => (string) $response['payment_url'],
                'provider_payload' => array_merge($payment->provider_payload ?? [], [
                    'cinetpay_init_response' => $response,
                    'cinetpay_request' => $paymentBody,
                    'cinetpay_transaction_id' => $response['transaction_id'] ?? null,
                ]),
            ]);

            return redirect()->away($payment->payment_url);
        }

        return Storage::disk('public')->download($document->fichier, $document->nom_fichier);
    }

    /**
     * Message affiché quand l’API CinetPay refuse l’initialisation (ex. 422).
     */
    private function cinetPayInitErrorMessage(array $response): string
    {
        if (isset($response['message']) && is_string($response['message']) && $response['message'] !== '') {
            return $response['message'];
        }

        if (isset($response['error']) && is_string($response['error']) && $response['error'] !== '') {
            return $response['error'];
        }

        if (isset($response['errors']) && is_array($response['errors'])) {
            foreach ($response['errors'] as $err) {
                if (is_string($err)) {
                    return $err;
                }
                if (is_array($err)) {
                    $first = reset($err);
                    if (is_string($first)) {
                        return $first;
                    }
                }
            }
        }

        $code = $response['code'] ?? null;
        if (is_numeric($code) && (int) $code !== 200) {
            return 'Erreur CinetPay (code ' . (int) $code . '). Réessayez ou contactez le support.';
        }

        return 'Erreur lors de l\'initialisation du paiement. Réessayez.';
    }

    /**
     * Prénom/nom CinetPay : minimum 2 caractères.
     */
    private function cinetPayClientName(string $value, string $fallback): string
    {
        $t = trim($value);
        if (mb_strlen($t) < 2) {
            return $fallback;
        }

        return mb_substr($t, 0, 255);
    }
}
