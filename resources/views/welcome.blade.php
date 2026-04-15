<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur Fobmir | Portail de Gestion Administrative</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-gold: #b68c36;
            --secondary-blue: #171e4c;
            --dark-blue: #0b0f2a;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: var(--dark-blue);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Effet de fond lumineux */
        body::before {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(182, 140, 54, 0.15) 0%, rgba(23, 30, 76, 0) 70%);
            top: -150px;
            right: -150px;
            z-index: 0;
        }

        .hero-section {
            position: relative;
            z-index: 1;
            width: 100%;
        }

        .logo-container {
            margin-bottom: 3rem;
        }

        .logo-icon {
            font-size: 3.5rem;
            color: var(--primary-gold);
            margin-bottom: 1rem;
            filter: drop-shadow(0 0 15px rgba(182, 140, 54, 0.4));
        }

        .logo-text {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: white;
        }

        .logo-text span {
            color: var(--primary-gold);
        }

        /* Cartes d'accès */
        .access-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2.5rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            text-decoration: none;
            display: block;
            color: white;
        }

        .access-card:hover {
            transform: translateY(-12px);
            background: rgba(255, 255, 255, 0.07);
            border-color: var(--primary-gold);
            color: white;
        }

        .icon-box {
            width: 70px;
            height: 70px;
            background: rgba(182, 140, 54, 0.1);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--primary-gold);
            margin-bottom: 1.5rem;
            transition: 0.3s;
        }

        .access-card:hover .icon-box {
            background: var(--primary-gold);
            color: white;
            box-shadow: 0 10px 20px rgba(182, 140, 54, 0.3);
        }

        .btn-access {
            margin-top: 1.5rem;
            padding: 10px 25px;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            transition: 0.3s;
        }

        .access-card:hover .btn-access {
            background: white;
            color: var(--secondary-blue);
            border-color: white;
        }

        .footer-text {
            position: absolute;
            bottom: 30px;
            width: 100%;
            text-align: center;
            font-size: 0.8rem;
            opacity: 0.5;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

<div class="container hero-section">
    <div class="row justify-content-center text-center">
        <div class="col-lg-12 logo-container">
            <div class="logo">
                <i class="fas fa-shield-halved logo-icon"></i>
                <div class="logo-text">Fob<span>mir</span></div>
            </div>
            <p class="text-white-50 mt-2">Système Sécurisé de Gestion Documentaire Civile</p>
        </div>
    </div>

    <div class="row g-4 justify-content-center mt-2">
        <div class="col-md-5 col-lg-4">
            <a href="{{ route('auto-enregistrement.login') }}" class="access-card">
                <div class="icon-box">
                    <i class="fas fa-user-check"></i>
                </div>
                <h4 class="fw-bold">Espace Citoyen</h4>
                <p class="text-white-50 small mb-0">Accédez à vos classeurs personnels, téléchargez vos documents et gérez votre profil civil en toute sécurité.</p>
                <div class="btn-access">
                    <span>Se connecter</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
        </div>

        <div class="col-md-5 col-lg-4">
            <a href="{{ route('login') }}" class="access-card">
                <div class="icon-box">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h4 class="fw-bold">Point Focal</h4>
                <p class="text-white-50 small mb-0">Interface d'administration pour la gestion des ménages, la validation des documents et le suivi statistique.</p>
                <div class="btn-access">
                    <span>Espace Admin</span>
                    <i class="fas fa-lock"></i>
                </div>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>