<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CinetPayClient
{
    /**
     * @return array{verify: bool}
     */
    public function httpOptions(): array
    {
        $sslVerify = true;
        $cainfo = ini_get('curl.cainfo');
        if (is_string($cainfo) && $cainfo !== '' && !file_exists($cainfo)) {
            $sslVerify = false;
        }

        return ['verify' => $sslVerify];
    }

    public function baseUrl(): string
    {
        return rtrim((string) config('cinetpay.base_url'), '/');
    }

    public function forgetAccessToken(): void
    {
        Cache::forget((string) config('cinetpay.cache_key'));
    }

    public function getAccessToken(): ?string
    {
        $cacheKey = (string) config('cinetpay.cache_key');
        $ttl = (int) config('cinetpay.token_cache_seconds', 82800);

        $cached = Cache::get($cacheKey);
        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        $url = $this->baseUrl() . '/v1/oauth/login';

        /** L’API attend du JSON ; sans asJson(), Laravel envoie du x-www-form-urlencoded. */
        $response = Http::timeout(25)
            ->withOptions($this->httpOptions())
            ->acceptJson()
            ->asJson()
            ->post($url, [
                'api_key' => config('cinetpay.api_key'),
                'api_password' => config('cinetpay.api_password'),
            ]);

        $json = $response->json();
        if (!is_array($json)) {
            Log::warning('CinetPay OAuth: réponse non-JSON', [
                'status' => $response->status(),
                'snippet' => substr($response->body(), 0, 500),
            ]);

            return null;
        }

        $token = $this->extractOAuthAccessToken($json);
        if ($token === null || $token === '') {
            Log::warning('CinetPay OAuth login failed', [
                'http_status' => $response->status(),
                'body' => $this->sanitizeLogPayload($json),
            ]);

            return null;
        }

        if (!$response->successful()) {
            Log::warning('CinetPay OAuth: réponse HTTP non valide malgré un jeton', [
                'http_status' => $response->status(),
                'body' => $this->sanitizeLogPayload($json),
            ]);

            return null;
        }

        if (!$this->isOAuthResponseSuccessful($json)) {
            Log::info('CinetPay OAuth: format de réponse inhabituel (jeton accepté)', [
                'body' => $this->sanitizeLogPayload($json),
            ]);
        }

        Cache::put($cacheKey, $token, now()->addSeconds($ttl));

        return $token;
    }

    /**
     * Doc v1 : code 200 + access_token. Autres variantes possibles selon environnement.
     *
     * @param  array<string, mixed>  $json
     */
    private function extractOAuthAccessToken(array $json): ?string
    {
        if (!empty($json['access_token']) && is_string($json['access_token'])) {
            return $json['access_token'];
        }

        if (isset($json['data']['access_token']) && is_string($json['data']['access_token'])) {
            return $json['data']['access_token'];
        }

        if (isset($json['data']['token']) && is_string($json['data']['token'])) {
            return $json['data']['token'];
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $json
     */
    private function isOAuthResponseSuccessful(array $json): bool
    {
        $status = strtoupper((string) ($json['status'] ?? ''));
        if ($status === 'OK') {
            return true;
        }

        $code = $json['code'] ?? null;
        if (is_numeric($code)) {
            $c = (int) $code;
            if ($c === 200) {
                return true;
            }
            if ($c === 0 && (strtoupper((string) ($json['message'] ?? '')) === 'OPERATION_SUCCES'
                || isset($json['data']['token']))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function sanitizeLogPayload(array $payload): array
    {
        $out = $payload;
        if (isset($out['access_token'])) {
            $out['access_token'] = '[masqué]';
        }
        if (isset($out['data']) && is_array($out['data']) && isset($out['data']['token'])) {
            $out['data']['token'] = '[masqué]';
        }
        if (isset($out['data']) && is_array($out['data']) && isset($out['data']['access_token'])) {
            $out['data']['access_token'] = '[masqué]';
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    public function initPayment(array $body): array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return ['_error' => 'oauth_failed'];
        }

        return $this->postPayment($body, $token);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    private function postPayment(array $body, string $accessToken): array
    {
        $url = $this->baseUrl() . '/v1/payment';
        $response = Http::timeout(35)
            ->withOptions($this->httpOptions())
            ->withToken($accessToken)
            ->acceptJson()
            ->asJson()
            ->post($url, $body);

        $json = $response->json() ?? [];
        if (!is_array($json)) {
            $json = [];
        }
        $json['_http_status'] = $response->status();

        if ($response->status() === 401) {
            $this->forgetAccessToken();
            $token = $this->getAccessToken();
            if ($token) {
                $response = Http::timeout(35)
                    ->withOptions($this->httpOptions())
                    ->withToken($token)
                    ->acceptJson()
                    ->asJson()
                    ->post($url, $body);
                $json = $response->json() ?? [];
                if (!is_array($json)) {
                    $json = [];
                }
                $json['_http_status'] = $response->status();
            }
        }

        return $json;
    }

    /**
     * @return array<string, mixed>
     */
    public function getPaymentStatus(string $paymentToken): array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return ['_error' => 'oauth_failed'];
        }

        return $this->fetchPaymentStatus($paymentToken, $token);
    }

    /**
     * @return array<string, mixed>
     */
    private function fetchPaymentStatus(string $paymentToken, string $accessToken): array
    {
        $url = $this->baseUrl() . '/v1/payment/' . rawurlencode($paymentToken);
        $response = Http::timeout(25)
            ->withOptions($this->httpOptions())
            ->withToken($accessToken)
            ->acceptJson()
            ->get($url);

        $json = $response->json() ?? [];

        if ($response->status() === 401) {
            $this->forgetAccessToken();
            $token = $this->getAccessToken();
            if ($token) {
                $response = Http::timeout(25)
                    ->withOptions($this->httpOptions())
                    ->withToken($token)
                    ->acceptJson()
                    ->get($url);
                $json = $response->json() ?? [];
            }
        }

        return $json;
    }
}
