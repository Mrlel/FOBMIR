<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Sécurisé - KindFlow</title>
    <script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --teal-dark: #0E3D38;
            --teal-primary: #1B5E57;
            --orange-kind: #F06A1D;
            --bg-soft: #F8F9FA;
            --text-main: #2D3748;
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-soft);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .payment-box {
            background: #ffffff;
            width: 100%;
            max-width: 480px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            padding: 40px;
            border: 1px solid #E2E8F0;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-icon {
            color: var(--teal-primary);
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .title {
            color: var(--teal-dark);
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .price-container {
            text-align: center;
            background: rgba(27, 94, 87, 0.03);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid rgba(27, 94, 87, 0.05);
        }

        .price {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--orange-kind);
        }

        .doc-name {
            color: var(--teal-primary);
            font-size: 0.95rem;
            font-weight: 600;
            margin-top: 5px;
        }

        .details-grid {
            margin-bottom: 35px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid #F1F1F1;
            font-size: 0.9rem;
        }

        .label {
            color: #718096;
            font-weight: 500;
        }

        .value {
            color: var(--text-main);
            font-weight: 600;
        }

        #pay-btn {
            background-color: var(--teal-primary);
            color: #ffffff;
            width: 100%;
            border: none;
            padding: 16px;
            font-size: 1rem;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        #pay-btn:hover {
            background-color: var(--teal-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(27, 94, 87, 0.2);
        }

        .secure-badge {
            text-align: center;
            margin-top: 25px;
            font-size: 0.75rem;
            color: #A0AEC0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }

        .secure-badge i {
            color: #38A169;
        }
    </style>
</head>
<body>

    <div class="payment-box">
        <div class="header">
            <div class="brand-icon">
                <i class="bi bi-shield-check"></i>
            </div>
            <h1 class="title">Récapitulatif de la commande</h1>
        </div>

        <div class="price-container">
            <div class="price">100 FCFA</div>
            <div class="doc-name text-truncate">{{ $payment->document->libelle }}</div>
        </div>

        <div class="details-grid">
            <div class="detail-item">
                <span class="label">Identité</span>
                <span class="value">{{ $payment->nom_acheteur }} {{ $payment->prenom_acheteur }}</span>
            </div>
            <div class="detail-item">
                <span class="label">Email</span>
                <span class="value">{{ $payment->email_acheteur }}</span>
            </div>
            <div class="detail-item">
                <span class="label">Contact</span>
                <span class="value">{{ $payment->telephone_acheteur }}</span>
            </div>
        </div>

        <button id="pay-btn">
            <i class="bi bi-credit-card"></i> PROCÉDER AU PAIEMENT
        </button>

        <div class="secure-badge">
            <i class="bi bi-lock-fill"></i> Cryptage SSL 256-bits — KindFlow & FedaPay
        </div>
    </div>

    <script>
        const paymentId = {{ $payment->id }};
        FedaPay.init('#pay-btn', {
            public_key: '{{ env("FEDAPAY_PUBLIC_KEY") }}',
            transaction: {
                amount: 100,
                description: 'Accès document: {{ $payment->document->libelle }}',
                custom_metadata: { payment_id: paymentId }
            },
            customer: {
                email: '{{ $payment->email_acheteur }}',
                firstname: '{{ $payment->nom_acheteur }}',
                lastname: '{{ $payment->prenom_acheteur }}',
                phone_number: { number: '{{ $payment->telephone_acheteur }}', country: 'CI' }
            },
            onComplete: function(response) {
                if (response.transaction && response.transaction.status === 'approved') {
                    window.location.href = '/payment/verify?transaction_id=' + response.transaction.id + '&payment_id=' + paymentId;
                } else {
                    window.location.href = '/payment/failed/' + paymentId;
                }
            }
        });
    </script>
</body>
</html>