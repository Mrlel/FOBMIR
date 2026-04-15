<?php

return [
    /*
    | Sandbox: https://api.cinetpay.net — Production: voir documentation CinetPay.
    */
    'base_url' => rtrim((string) env('CINETPAY_BASE_URL', 'https://api.cinetpay.net'), '/'),

    /** trim() évite les échecs silencieux si des espaces traînent dans le .env */
    'api_key' => trim((string) env('CINETPAY_API_KEY', '')),
    'api_password' => trim((string) env('CINETPAY_API_PASSWORD', '')),

    /** Devise du compte marchand (ex. XOF, XAF). */
    'currency' => env('CINETPAY_CURRENCY', 'XOF'),

    'lang' => env('CINETPAY_LANG', 'fr'),

    'download_price' => (int) env('CINETPAY_DOWNLOAD_PRICE', 200),

    /** Libellé affiché sur la passerelle. */
    'designation' => env('CINETPAY_DESIGNATION', 'Téléchargement de document'),

    /**
     | Email utilisé si l’individu n’a pas d’email valide (client_email requis par l’API).
     */
    'fallback_email' => env('CINETPAY_FALLBACK_EMAIL', 'client@example.com'),

    /** Cache du jeton OAuth (secondes, un peu inférieur à expires_in). */
    'token_cache_seconds' => (int) env('CINETPAY_TOKEN_CACHE_SECONDS', 82800),

    'cache_key' => 'cinetpay_access_token',
];
