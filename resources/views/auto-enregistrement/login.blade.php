<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruitify - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #072b3e;
            --primary-green: #2ecc71;
            --input-bg: rgba(255, 255, 255, 0.1);
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-dark);
            color: white;
            overflow: hidden;
        }

        /* Arrière-plan avec vagues en bas */
        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, #0b0f2a 85%, #000000 100%);
            position: relative;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            text-align: center;
            z-index: 10;
        }

        .logo-top-left {
            position: absolute;
            top: 20px;
            left: 20px;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .lang-selector {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 0.9rem;
        }

        /* Styles des champs de saisie */
        .form-control {
            background-color: var(--input-bg);
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.15);
            box-shadow: none;
            color: white;
            border: 1px solid var(--primary-green);
        }

        .form-control::placeholder {
            color: #adb5bd;
            font-size: 0.9rem;
        }

        /* Bouton Login */
        .btn-login {
            background-color: var(--primary-green);
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
        }

        .links-container {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            margin-top: 10px;
            color: #adb5bd;
        }

        .links-container a {
            color: var(--primary-green);
            text-decoration: none;
        }

        .footer-text {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
            color: #888;
            font-size: 0.75rem;
        }
    </style>
</head>
<body>

    <div class="logo-top-left">
        <span style="color: white;">R</span><span style="color: var(--primary-green);">ecruitify</span>
    </div>

    <div class="lang-selector">
        🇬🇧 EN
    </div>

    <div class="login-container">
        <div class="login-card p-4">
            <div class="mb-4">
                <svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 6V12L16 14" stroke="#2ecc71" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <h1 class="h2 mt-3">Sign in</h1>
                <p class="small text-secondary">Sign in and start managing your candidates!</p>
            </div>

             <form method="POST" action="{{ route('auto-enregistrement.login.post') }}">
                    @csrf

                    <div class="form-floating">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required autofocus>
                        <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        <label for="password"><i class="fas fa-lock me-2"></i>Mot de passe</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <a href="{{ route('auto-enregistrement.register') }}">Créer un compte</a>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                    </button>
                </form>
        </div>
    </div>

    <div class="footer-text">
        2018 © Recruitify. All rights reserved.<br>
        Designed by Lukasz Swierad
    </div>

</body>
</html>