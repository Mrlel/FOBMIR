<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>MyPapyrus – Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --orange: #F06A1D;
            --orange-light: #FF8C42;
            --teal: #1B5E57;
            --teal-dark: #0E3D38;
            --cream: #FBF7F2;
            --warm-white: #FFFDF9;
            --text-dark: #1A1A1A;
            --text-muted: #6B7280;
        }

        body { font-family: 'DM Sans', sans-serif; background: var(--cream); color: var(--text-dark); min-height: 100vh; overflow-x: hidden; }
        
        /* Panneau Gauche */
        .login-left {
            background: linear-gradient(155deg, var(--teal-dark) 0%, var(--teal) 60%);
            padding: 50px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100vh;
            position: relative;
        }

        .left-title { font-family: 'Playfair Display', serif; font-size: 2.8rem; font-weight: 900; line-height: 1.2; }
        .left-title em { color: var(--orange-light); font-style: normal; }
        
        .impact-pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
            padding: 8px 16px; border-radius: 50px; font-size: 0.85rem; margin-bottom: 10px;
        }

        /* Panneau Droit */
        .login-right { background: var(--warm-white); padding: 60px 40px; display: flex; align-items: center; justify-content: center; }
        .auth-card { width: 100%; max-width: 420px; animation: fadeIn 0.6s ease-out; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .form-control {
            background: var(--cream); border: 1.5px solid #e8e2da; border-radius: 12px;
            padding: 12px 16px; font-size: 0.9rem; transition: all 0.3s;
        }

        .form-control:focus { border-color: var(--teal); box-shadow: 0 0 0 4px rgba(27,94,87,0.1); background: #fff; }

        .btn-primary {
            background: var(--orange); border: none; border-radius: 12px; padding: 14px;
            font-weight: 700; letter-spacing: 0.5px; transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(240,106,29,0.3);
        }

        .btn-primary:hover { background: var(--orange-light); transform: translateY(-2px); }

        .input-group-text { background: var(--cream); border: 1.5px solid #e8e2da; border-right: none; color: var(--text-muted); border-radius: 12px 0 0 12px; }
        .form-control-with-icon { border-left: none; border-radius: 0 12px 12px 0; }

        .auth-footer { text-align: center; margin-top: 30px; font-size: 0.9rem; color: var(--text-muted); }
        .auth-footer a { color: var(--orange); font-weight: 600; text-decoration: none; }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-lg-6 d-none d-lg-flex">
            <div class="login-left w-100">
                <div>
                      <a class="navbar-brand" href="/">
                            <img src="/logo_p.jpeg" height="90" alt="Paysage rural">
                        </a>
                    <div class="left-eyebrow mb-2 text-uppercase fw-bold pt-2" style="letter-spacing:2px; font-size:0.7rem; color:var(--orange-light)">Système d'Archivage Rural</div>
                    <h1 class="left-title mb-4">Protégez votre <em>patrimoine</em> documentaire.</h1>
                    <p class="opacity-75 mb-5" style="max-width: 450px; line-height: 1.7;">
                        L'outil de confiance pour la sauvegarde numérique des actes d'état civil et des titres fonciers en milieu rural.
                    </p>
                    
                    <div class="d-flex flex-wrap gap-2">
                        <div class="impact-pill"><i class="bi bi-shield-lock"></i> Sécurité maximale</div>
                        <div class="impact-pill"><i class="bi bi-geo-alt"></i> Géolocalisation</div>
                        <div class="impact-pill"><i class="bi bi-cloud-check"></i> Accès permanent</div>
                    </div>
                </div>
                
                <div class="pt-4 border-top border-white border-opacity-10">
                    <p class="small opacity-50 m-0">&copy; 2026 Projet FOBMIR. Tous droits réservés.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-6 login-right">
            <div class="auth-card">
                <div class="text-center mb-5 d-lg-none">
                    <h2 style="font-family:'Playfair Display',serif; font-weight:900; color:var(--teal-dark)">
                        <i class="bi bi-heart-fill me-2" style="color:var(--orange)"></i>MyPapyrus
                    </h2>
                </div>

                <h2 class="mb-2" style="font-family:'Playfair Display',serif; font-weight:900;">Connexion</h2>
                <p class="text-muted mb-4">Accédez à votre espace d'archivage sécurisé.</p>

                @if(session('error'))
                    <div class="alert alert-danger border-0 rounded-4 mb-4 small">
                        <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('auto-enregistrement.login.post') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small">Adresse Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control form-control-with-icon" 
                                   placeholder="nom@exemple.com" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label fw-bold small">Mot de passe</label>
                            <a href="#" class="small text-decoration-none" style="color:var(--orange)">Oublié ?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control form-control-with-icon" 
                                   placeholder="••••••••" required>
                            <button type="button" class="btn border-1 border-start-0" 
                                    style="border-color:#e8e2da; background:var(--cream); border-radius: 0 12px 12px 0;"
                                    onclick="togglePassword()">
                                <i class="bi bi-eye text-muted" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label small text-muted" for="remember">Rester connecté</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        SE CONNECTER <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </form>

                <div class="auth-footer">
                    Pas encore de compte ? <a href="{{ route('auto-enregistrement.register') }}">Créer un compte</a>
                </div>
                
                <div class="mt-5 d-flex justify-content-center gap-4 opacity-50">
                    <i class="bi bi-shield-fill-check h4" title="SSL Sécurisé"></i>
                    <i class="bi bi-fingerprint h4" title="Authentification forte"></i>
                    <i class="bi bi-hdd-network-fill h4" title="Archivage Cloud"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>

</body>
</html>