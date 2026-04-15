<?php

namespace App\Http\Controllers;

use App\Models\DocumentPayment;
use App\Services\CinetPayClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CinetPayController extends Controller
{
    public function __construct(
        private readonly CinetPayClient $cinetPay
    ) {}

    public function retour(DocumentPayment $payment)
    {
        if (!$payment->token || $payment->provider !== 'cinetpay') {
            return redirect()->route('mes.classeurs')->with('error', 'Paiement introuvable.');
        }

        $data = $this->cinetPay->getPaymentStatus($payment->token);
        if (!isset($data['_error'])) {
            $this->updatePaymentFromStatusResponse($payment, $data);
        }

        if ($payment->fresh()->status === 'paid') {
            $classeurId = $payment->provider_payload['classeur_id'] ?? null;
            if ($classeurId) {
                return redirect()
                    ->route('individu.classeurs.documents.download', [$classeurId, $payment->document_id])
                    ->with('success', 'Paiement confirmé. Téléchargement autorisé.');
            }

            return redirect()->route('mes.classeurs')->with('success', 'Paiement confirmé.');
        }

        return redirect()->route('mes.classeurs')->with('info', 'Paiement en cours de validation. Réessayez dans quelques instants.');
    }

    public function echec(DocumentPayment $payment)
    {
        return redirect()->route('mes.classeurs')->with('error', 'Le paiement a échoué ou a été annulé.');
    }

    public function notify(Request $request)
    {
        if ($request->isMethod('get')) {
            return response('OK', 200);
        }

        $payload = $request->all();
        if ($payload === [] && $request->getContent() !== '') {
            $decoded = json_decode($request->getContent(), true);
            if (is_array($decoded)) {
                $payload = $decoded;
            }
        }

        $merchantId = $payload['merchant_transaction_id']
            ?? $payload['cpm_custom']
            ?? null;

        $paymentToken = $payload['payment_token']
            ?? $payload['token']
            ?? null;

        $payment = null;
        if (is_string($merchantId) && $merchantId !== '') {
            $payment = DocumentPayment::query()
                ->where('provider', 'cinetpay')
                ->where('provider_payload->merchant_transaction_id', $merchantId)
                ->first();
        }

        if (!$payment && is_string($paymentToken) && $paymentToken !== '') {
            $payment = DocumentPayment::query()
                ->where('provider', 'cinetpay')
                ->where('token', $paymentToken)
                ->first();
        }

        if (!$payment) {
            return response('OK', 200);
        }

        if ($payment->status === 'paid') {
            return response('OK', 200);
        }

        try {
            if ($payment->token) {
                $data = $this->cinetPay->getPaymentStatus($payment->token);
                if (!isset($data['_error'])) {
                    $this->updatePaymentFromStatusResponse($payment, $data, $payload);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('CinetPay notify error: ' . $e->getMessage(), ['payload' => $payload]);

            return response('ERR', 500);
        }

        return response('OK', 200);
    }

    /**
     * @param  array<string, mixed>  $statusResponse
     * @param  array<string, mixed>  $notifyPayload
     */
    private function updatePaymentFromStatusResponse(DocumentPayment $payment, array $statusResponse, array $notifyPayload = []): void
    {
        $status = strtoupper((string) ($statusResponse['status'] ?? ''));

        $newStatus = match ($status) {
            'SUCCESS' => 'paid',
            'FAILED' => 'failure',
            default => 'pending',
        };

        $providerPayload = $payment->provider_payload ?? [];
        $providerPayload['cinetpay_status_response'] = $statusResponse;
        if ($notifyPayload !== []) {
            $providerPayload['cinetpay_notify_payload'] = $notifyPayload;
        }

        $payment->update([
            'status' => $newStatus,
            'provider_payload' => $providerPayload,
            'paid_at' => $newStatus === 'paid' ? now() : null,
        ]);
    }
}
