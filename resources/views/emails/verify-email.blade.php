<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Vérification de votre adresse email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eeeeee;
        }

        .header h1 {
            color: #333333;
        }

        .content {
            padding: 20px 0;
            color: #555555;
            line-height: 1.6;
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .button {
            background-color: #28a745;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #999999;
            margin-top: 20px;
            border-top: 1px solid #eeeeee;
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Bienvenue, {{ $individu->prenom }} !</h1>
        </div>
        <div class="content">
            <p>Merci de vous être inscrit sur FOBMIR. Pour activer pleinement votre compte et vérifier votre adresse
                email, veuillez cliquer sur le bouton ci-dessous :</p>

            <div class="button-container">
                <a href="{{ route('auto-enregistrement.verify', $individu->verification_token) }}"
                    class="button">Vérifier mon email</a>
            </div>

            <p>Si vous n'avez pas créé de compte, vous pouvez ignorer cet email.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} FOBMIR. Tous droits réservés.</p>
        </div>
    </div>
</body>

</html>