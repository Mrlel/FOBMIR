<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changement de mot de passe | Fobmir</title>

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
            background: var(--bg-light);
            min-height: 100vh;
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
            box-shadow: 0 10px 40px rgba(11, 15, 42, 0.08);
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

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: var(--secondary-blue);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
        }
        .submit-btn:hover { background: var(--dark-blue); color: white; }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="admin-icon">
                <i class="fas fa-key"></i>
            </div>
            <h4>Changement de mot de passe</h4>
            <p class="text-muted small">
                Pour des raisons de sécurité, vous devez définir un nouveau mot de passe avant de continuer.
            </p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        <form action="{{ route('password.change.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="current_password" class="form-label">Mot de passe actuel</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Nouveau mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-check me-2"></i> VALIDER
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
