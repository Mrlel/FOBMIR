<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Le webhook FedaPay (routes/web_independant.php) est exempté directement
        // sur sa route via ->withoutMiddleware([...]) et protégé par vérification
        // de signature (voir DocumentPaymentController::webhook).
    ];
}
