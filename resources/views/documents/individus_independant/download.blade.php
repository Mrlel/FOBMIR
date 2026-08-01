<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès au document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #fcfcfc;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #212529;
        }
        .admin-card {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            background: #fff;
            max-width: 500px;
            width: 100%;
            margin-top: 10vh;
        }
        .status-bar {
            background-color: #1a433e; /* Teal sombre très pro */
            height: 4px;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            border-radius: 4px 4px 0 0;
        }
        .btn-main {
            background-color: #1a433e;
            color: white;
            border-radius: 2px;
            padding: 12px 24px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            border: none;
        }
        .btn-main:hover {
            background-color: #13322e;
            color: white;
        }
        .btn-link-back {
            color: #6c757d;
            text-decoration: none;
            font-size: 0.85rem;
            border-bottom: 1px solid transparent;
        }
        .btn-link-back:hover {
            border-bottom: 1px solid #6c757d;
        }
        .info-table {
            background: #f8f9fa;
            border: 1px solid #edf0f2;
            padding: 15px;
            text-align: left;
            font-size: 0.9rem;
            margin-bottom: 25px;
        }
    </style>
</head>
<body class="d-flex justify-content-center p-3">

    <div class="card admin-card p-4 p-md-5 position-relative shadow-sm">
        <div class="status-bar"></div>
        
        <div class="mb-4">
            <h5 class="fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Confirmation de transaction</h5>
            <p class="text-muted small">Le paiement a été validé. Votre document est prêt pour le téléchargement.</p>
        </div>

        <div class="info-table">
            <div class="row mb-2">
                <div class="col-4 text-muted">Document :</div>
                <div class="col-8 fw-bold">{{ $payment->document->libelle }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-4 text-muted">Référence :</div>
                <div class="col-8 text-monospace small">{{ $payment->token_telechargement }}</div>
            </div>
            <div class="row">
                <div class="col-4 text-muted">Destinataire :</div>
                <div class="col-8">{{ $payment->email_acheteur }}</div>
            </div>
        </div>

        <a href="{{ route('documents.download.file', $payment->token_telechargement) }}"
           class="btn btn-main w-100 mb-4">
            Télécharger le document (PDF)
        </a>

        <div class="text-center">
            <a href="{{ route('mes.classeurs') }}" class="btn-link-back">
                Retour à l'espace personnel
            </a>
        </div>
    </div>

</body>
</html>