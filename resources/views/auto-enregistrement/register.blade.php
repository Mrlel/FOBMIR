<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruitify - Inscription</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #0b0f2a;
            --primary-green: #2ecc71;
            --input-bg: rgba(255, 255, 255, 0.1);

            
        }

        body, html {
            min-height: 100%;
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-dark);
            color: white;
            margin: 0;
        }

        /* Fond dégradé Recruitify */
        .page-wrapper {
            background: linear-gradient(180deg, #0b0f2a 85%, #000000 100%);
            padding: 50px 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .register-card {
            width: 100%;
            max-width: 1050px;
            z-index: 10;
        }

        /* Logo Recruitify */
        .brand-logo {
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 30px;
        }

        /* Styles des titres de section */
        h6 {
            color: var(--primary-green);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1.5px;
            border-left: 3px solid var(--primary-green);
            padding-left: 12px;
            margin: 30px 0 20px 0;
        }

        /* Champs de saisie stylisés */
        .form-control, .form-select {
            background-color: var(--input-bg);
            border: 1px solid transparent;
            color: white !important;
            padding: 12px 15px;
            border-radius: 8px;
        }

        .form-control:focus, .form-select:focus {
            background-color: rgba(255, 255, 255, 0.15);
            border-color: var(--primary-green);
            box-shadow: 0 0 10px rgba(46, 204, 113, 0.2);
            color: white;
        }

        .form-control::placeholder { color: #adb5bd; }

        /* Correction pour les icônes de calendrier et select */
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        /* Carte Leaflet avec filtre sombre */
        #map {
            height: 300px;
            border-radius: 12px;
            border: 1px solid #ffffff;
            filter: invert(90%) hue-rotate(180deg) brightness(95%) contrast(90%);
        }

        /* Boutons */
        .btn-login {
            background-color: var(--primary-green);
            border: none;
            width: 100%;
            padding: 14px;
            border-radius: 30px;
            color: white;
            font-weight: 600;
            transition: 0.3s;
            margin-top: 20px;
        }

        .btn-login:hover {
            background-color: #27ae60;
            box-shadow: 0 0 20px rgba(46, 204, 113, 0.4);
            transform: translateY(-2px);
        }

        .btn-outline-success {
            border-color: var(--primary-green);
            color: var(--primary-green);
            border-radius: 20px;
        }

        /* Alertes */
        .alert-info-custom {
            background: rgba(46, 204, 113, 0.1);
            border: 1px dashed var(--primary-green);
            color: white;
            border-radius: 10px;
        }

        .footer-text {
            color: #888;
            font-size: 0.8rem;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="page-wrapper">
  
    <div class="register-card border-0 p-4 p-md-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Inscription</h2>
            <p class="text-secondary">Remplissez vos informations pour créer votre dossier</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show bg-danger text-white border-0" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('auto-enregistrement.register.post') }}" id="registerForm">
            @csrf

            <h6><i class="fas fa-user me-2"></i> Informations personnelles</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="prenom" placeholder="Prénom *" required>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="nom" placeholder="Nom *" required>
                </div>
                <div class="col-md-6">
                    <input type="email" class="form-control" name="email" placeholder="Email *" required>
                </div>
                <div class="col-md-6">
                    <input type="tel" class="form-control" name="telephone" placeholder="Téléphone">
                </div>
                <div class="col-md-6">
                    <label class="small text-secondary mb-1">Date de naissance</label>
                    <input type="date" class="form-control" name="date_naissance">
                </div>
                <div class="col-md-6">
                    <label class="small text-secondary mb-1">Sexe *</label>
                    <select class="form-select" name="sexe" required>
                        <option value="" disabled selected>Choisir...</option>
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="profession" placeholder="Profession">
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="adresse_complete" placeholder="Adresse complète">
                </div>
            </div>

            <h6><i class="fas fa-map-marker-alt me-2"></i> Localisation GPS</h6>
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <div class="text-center mb-3">
                <button type="button" class="btn btn-outline-success px-4" id="btnLocate">
                    <i class="fas fa-crosshairs me-2"></i> Me localiser sur la carte
                </button>
            </div>
            
            <div id="map" class="mb-4"></div>

            <h6><i class="fas fa-lock me-2"></i> Sécurité du compte</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="password" class="form-control" name="password" placeholder="Mot de passe *" required>
                </div>
                <div class="col-md-6">
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmation *" required>
                </div>
            </div>

            <button type="submit" class="btn btn-login shadow">
                <i class="fas fa-paper-plane me-2"></i> Finaliser mon inscription
            </button>
        </form>
    </div>

    <div class="footer-text">
        2026 © Recruitify. Tous droits réservés.
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map, marker;

    document.addEventListener('DOMContentLoaded', () => {
        initMap();

        // Validation avant soumission
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            if (!document.getElementById('latitude').value) {
                e.preventDefault();
                alert("Erreur : Veuillez utiliser le bouton « Me localiser » avant de valider.");
            }
        });
    });

    function initMap() {
        // Position par défaut (Abidjan)
        map = L.map('map').setView([5.348, -4.028], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        document.getElementById('btnLocate').addEventListener('click', () => {
            if (!navigator.geolocation) {
                alert("Votre navigateur ne supporte pas la géolocalisation.");
                return;
            }

            const btn = document.getElementById('btnLocate');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Recherche en cours...';

            navigator.geolocation.getCurrentPosition(pos => {
                const { latitude, longitude } = pos.coords;

                document.getElementById('latitude').value = latitude;
                document.getElementById('longitude').value = longitude;

                map.setView([latitude, longitude], 16);

                if (marker) {
                    marker.setLatLng([latitude, longitude]);
                } else {
                    marker = L.marker([latitude, longitude]).addTo(map);
                }
                
                btn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Position enregistrée';
                btn.classList.replace('btn-outline-success', 'btn-success');
                btn.classList.add('text-white');
            }, (err) => {
                alert("Erreur de localisation : " + err.message);
                btn.innerHTML = '<i class="fas fa-location-arrow me-1"></i> Réessayer';
            });
        });
    }
</script>

</body>
</html>