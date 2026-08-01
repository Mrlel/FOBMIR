<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement échoué</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-gold: #b68c36;
            --secondary-blue: #171e4c;
        }

        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .error-box {
            background: #ffffff;
            width: 100%;
            max-width: 450px;
            border-radius: 4px;
            border-top: 5px solid #dc3545; /* Ligne rouge pour l'erreur */
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 40px;
            text-align: center;
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            background: #fff5f5;
            color: #dc3545;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 20px;
            font-size: 2.5rem;
        }

        .title {
            color: var(--secondary-blue);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .message {
            color: #6c757d;
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 30px;
        }

        /* Bouton Principal en Or */
        .btn-retry {
            background-color: var(--primary-gold);
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
            border-radius: 4px;
            width: 100%;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            text-decoration: none;
            display: block;
        }

        .btn-retry:hover {
            background-color: #9e792e;
            color: white;
            transform: translateY(-1px);
        }

        /* Bouton Secondaire en Bleu */
        .btn-back {
            background-color: transparent;
            color: var(--secondary-blue);
            border: 1px solid var(--secondary-blue);
            padding: 15px;
            font-weight: 600;
            border-radius: 4px;
            width: 100%;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
        }

        .btn-back:hover {
            background-color: var(--secondary-blue);
            color: white;
        }

        .support-text {
            margin-top: 25px;
            font-size: 0.85rem;
            color: #adb5bd;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">

    <div class="error-box">
        <div class="icon-circle">
            <i class="bi bi-exclamation-circle"></i>
        </div>
        
        <h1 class="title">Transaction échouée</h1>
        
        <p class="message">
            Nous n'avons pas pu traiter votre paiement. Cela peut être dû à un solde insuffisant ou à une annulation de la transaction.
        </p>

        @if($payment)
            <a href="{{ route('documents.buy', $payment->document_id) }}" class="btn-retry">
                <i class="bi bi-arrow-clockwise me-2"></i> Réessayer le paiement
            </a>
        @endif

        <a href="{{ route('mes.classeurs') }}" class="btn-back">
            <i class="bi bi-house-door me-2"></i> Retour à l'accueil
        </a>

        <div class="support-text">
            Besoin d'aide ? Contactez notre support technique.
        </div>
    </div>

</body>
</html>