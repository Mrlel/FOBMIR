<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>MyPapyrus – Inscription Administrative</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

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

        /* Désactivation du scroll global */
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text-dark);
        }

        .main-row {
            height: 100vh;
        }

        /* --- PANNEAU GAUCHE : FIXE --- */
        .login-left {
            background: linear-gradient(155deg, var(--teal-dark) 0%, var(--teal) 60%);
            padding: 60px 50px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
        }

        .left-title { 
            font-family: 'Playfair Display', serif; 
            font-size: 2.5rem; 
            font-weight: 900; 
            line-height: 1.2; 
        }
        .left-title em { color: var(--orange-light); font-style: normal; }

        /* --- PANNEAU DROIT : SCROLLABLE --- */
        .login-right {
            background: var(--warm-white);
            padding: 50px 40px;
            height: 100vh;
            overflow-y: auto; /* Scroll interne activé */
        }

        /* Personnalisation de la barre de défilement */
        .login-right::-webkit-scrollbar { width: 8px; }
        .login-right::-webkit-scrollbar-track { background: var(--cream); }
        .login-right::-webkit-scrollbar-thumb { 
            background: var(--teal); 
            border-radius: 10px; 
            border: 2px solid var(--warm-white);
        }

        .auth-card { 
            width: 100%; 
            max-width: 750px; 
            margin: 0 auto; 
            animation: fadeIn 0.6s ease-out; 
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* --- STYLES FORMULAIRE --- */
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--teal-dark);
            margin: 40px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--cream);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .section-title i { color: var(--orange); }

        .form-label { font-weight: 600; font-size: 0.85rem; margin-bottom: 8px; }
        .form-control, .form-select {
            background: var(--cream);
            border: 1.5px solid #e8e2da;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        .form-control:focus { 
            border-color: var(--teal); 
            box-shadow: 0 0 0 4px rgba(27,94,87,0.1); 
            background: #fff; 
        }

        /* --- CARTE & GPS --- */
        #map { 
            height: 300px; 
            border-radius: 15px; 
            border: 1.5px solid #e8e2da; 
            margin-bottom: 20px; 
            z-index: 1;
        }
        .btn-locate {
            background: var(--teal);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(27,94,87,0.2);
        }
        .btn-locate:hover { background: var(--teal-dark); transform: translateY(-2px); }

        .btn-submit {
            background: var(--orange);
            color: white;
            border: none;
            border-radius: 14px;
            padding: 18px;
            font-weight: 800;
            width: 100%;
            margin-top: 40px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 6px 20px rgba(240,106,29,0.3);
            transition: all 0.3s;
        }
        .btn-submit:hover { background: var(--orange-light); transform: translateY(-3px); }

        .auth-footer { text-align: center; margin-top: 30px; font-size: 0.95rem; color: var(--text-muted); }
        .auth-footer a { color: var(--orange); font-weight: 700; text-decoration: none; }

        /* Ajustements Mobile */
        @media (max-width: 991.98px) {
            html, body { overflow: auto; height: auto; }
            .main-row { height: auto; }
            .login-left { height: auto; padding: 40px 20px; }
            .login-right { height: auto; overflow: visible; padding: 40px 20px; }
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0 main-row">
        
        <div class="col-lg-4 d-none d-lg-flex">
            <div class="login-left w-100">
                <div>
                    <a class="navbar-brand" href="/">
        <img src="/logo_p.jpeg" height="90" alt="Paysage rural">
    </a>
                    <h1 class="left-title mb-4">Rejoignez le réseau d'<em>archivage</em>.</h1>
                    <p class="opacity-75 fs-5">Sécurisez vos documents fonciers et administratifs en quelques minutes.</p>
                    
                    <div class="mt-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-opacity-10 p-3 me-3">
                                <i class="bi bi-shield-check h4 m-0 text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Confidentialité Totale</h6>
                                <p class="small opacity-50 mb-0">Vos données sont cryptées.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-opacity-10 p-3 me-3">
                                <i class="bi bi-geo-alt h4 m-0 text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Preuve de Localisation</h6>
                                <p class="small opacity-50 mb-0">Certification GPS de vos titres.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="pt-4 border-top border-white border-opacity-10">
                    <p class="small opacity-50 m-0">&copy; 2026 MyPapyrus | Supporté par l'ONG FOBMIR</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8 login-right">
            <div class="auth-card">
                
                <div class="mb-5">
                    <h2 style="font-family:'Playfair Display',serif; font-weight:900; font-size: 2.2rem;">Création de Compte</h2>
                    <p class="text-muted">Identification au système national d'archivage rural.</p>
                </div>

                <form method="POST" action="{{ route('auto-enregistrement.register.post') }}" id="registerForm">
                    @csrf

                    <div class="section-title">
                        <i class="bi bi-person-bounding-box"></i> État Civil
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Prénom(s) *</label>
                            <input type="text" class="form-control" name="prenom" required placeholder="Ex: Jean-Baptiste">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nom *</label>
                            <input type="text" class="form-control" name="nom" required placeholder="Ex: Kouamé">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de naissance *</label>
                            <input type="date" class="form-control" name="date_naissance" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sexe *</label>
                            <select class="form-select" name="sexe" required>
                                <option value="" disabled selected>Choisir...</option>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profession principale</label>
                            <input type="text" class="form-control" name="profession" placeholder="Ex: Planteur">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">N° Téléphone</label>
                            <input type="tel" class="form-control" name="telephone" placeholder="Numero de telephone ...">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Adresse Email * <span class="fw-normal text-muted">(Sera votre identifiant)</span></label>
                            <input type="email" class="form-control" name="email" required placeholder="nom@exemple.com">
                        </div>
                    </div>

                    <div class="section-title">
                        <i class="bi bi-pin-map-fill"></i> Localisation Géo-référencée
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Ville</label>
                            <input type="text" class="form-control" name="adresse_complete" placeholder="Entrez le nom de votre ville...">
                        </div>
                        
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

                        <div class="col-12 text-center mt-4">
                            <div id="map"></div>
                            <button type="button" class="btn-locate" id="btnLocate">
                                <i class="bi bi-geo-fill me-2"></i> Capturer ma position GPS
                            </button>
                            <p class="small text-muted mt-3">
                                <i class="bi bi-info-circle me-1"></i> Cliquez pour certifier votre lieu de résidence actuel.
                            </p>
                        </div>
                    </div>

                    <div class="section-title">
                        <i class="bi bi-shield-lock-fill"></i> Sécurité du compte
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Mot de passe *</label>
                            <input type="password" class="form-control" name="password" required placeholder="8 caractères minimum">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmer le mot de passe *</label>
                            <input type="password" class="form-control" name="password_confirmation" required placeholder="Répétez le mot de passe">
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Créer mon espace <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </form>

                <div class="auth-footer mb-5">
                    Vous avez déjà un compte ? <a href="{{ route('auto-enregistrement.login') }}">Connectez-vous ici</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map, marker;

    document.addEventListener('DOMContentLoaded', () => {
        // Initialisation de la carte (Focus Abidjan par défaut)
        map = L.map('map').setView([5.348, -4.028], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        const btn = document.getElementById('btnLocate');

        btn.addEventListener('click', () => {
            if (!navigator.geolocation) {
                alert("Votre navigateur ne supporte pas la géolocalisation.");
                return;
            }

            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Localisation en cours...';
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(pos => {
                const { latitude, longitude } = pos.coords;
                document.getElementById('latitude').value = latitude;
                document.getElementById('longitude').value = longitude;

                map.setView([latitude, longitude], 16);
                if (marker) marker.setLatLng([latitude, longitude]);
                else marker = L.marker([latitude, longitude]).addTo(map);
                
                btn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> Position GPS validée';
                btn.style.background = '#198754'; // Couleur succès
                btn.disabled = false;
            }, (err) => {
                alert("Erreur GPS : " + err.message);
                btn.innerHTML = '<i class="bi bi-geo-fill me-2"></i> Réessayer la capture';
                btn.disabled = false;
            }, { enableHighAccuracy: true });
        });

        // Validation finale
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            if (!document.getElementById('latitude').value) {
                e.preventDefault();
                alert("La capture GPS est obligatoire pour finaliser l'inscription.");
                document.getElementById('map').scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>

</body>
</html>