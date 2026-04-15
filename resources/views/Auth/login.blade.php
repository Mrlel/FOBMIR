<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Point Focal | Fobmir</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-gold: #b68c36;
            --secondary-blue: #171e4c;
            --dark-blue: #0b0f2a;
            --bg-light: #f4f7fa;
        }

        body {
            font-family: 'Poppins', sans-serif;
 
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
   }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 24px;
            padding: 40px;

        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .admin-icon {
            width: 60px;
            height: 60px;
            background: rgba(182, 140, 54, 0.1);
            color: var(--primary-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            margin: 0 auto 15px;
            font-size: 1.8rem;
        }

        .login-header h4 {
            font-weight: 700;
            color: var(--secondary-blue);
            margin-bottom: 5px;
        }

        .form-label {
            font-weight: 600;
            color: var(--secondary-blue);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group-text {
            background: transparent;
            border-right: none;
            color: #a0aec0;
            border-radius: 12px 0 0 12px;
            padding-left: 15px;
        }

        .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
            padding: 12px 15px;
            font-size: 0.95rem;
            border-color: #dee2e6;
        }

        .form-control:focus {
            border-color: #dee2e6;
            box-shadow: none;
            background-color: #f8f9fa;
        }

        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: var(--primary-gold);
        }

        .submit-btn {
            background: var(--secondary-blue);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            width: 100%;
            font-weight: 600;
            letter-spacing: 1px;
            transition: 0.3s;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: var(--primary-gold);
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(182, 140, 54, 0.2);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 0.85rem;
            transition: 0.3s;
        }

        .back-link:hover {
            color: var(--primary-gold);
        }

        .forgot-link {
            color: var(--primary-gold);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="admin-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <h4>Espace Admin</h4>
            <p class="text-muted small">Veuillez vous identifier pour accéder au système.</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="telephone" class="form-label">N° Téléphone</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                    <input 
                        type="text" 
                        id="telephone" 
                        name="telephone" 
                        class="form-control" 
                        placeholder="01 02 03 04 05" 
                        required
                        pattern="[0-9\s]{10,}">
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="••••••••"
                        required>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember-me" name="remember">
                    <label class="form-check-label small text-muted" for="remember-me">Mémoriser</label>
                </div>
                <a href="/forgot-password" class="forgot-link">Oublié ?</a>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-sign-in-alt me-2"></i> CONNEXION
            </button>
        </form>
    </div>

    <a href="/" class="back-link">
        <i class="fas fa-chevron-left me-1"></i> Retour à l'accueil
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>