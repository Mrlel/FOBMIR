<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPHD-KOUMASSI | Espace Citoyen</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-light: #f4f7fa;
            --secondary-blue: #171e4c; /* Bleu Nuit */
            --primary-gold: #b68c36;    /* Or */
            --text-muted: #6c757d;
            --white: #ffffff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--secondary-blue);
            margin: 0;
            padding-bottom: 80px; 
        }

        /* ================= NAVBAR DESKTOP ================= */
        .navbar {
            background: var(--secondary-blue);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 0.8rem;
            border-bottom: 3px solid var(--primary-gold);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--white) !important;
            letter-spacing: 0.5px;
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
            transition: 0.3s;
            padding: 0.5rem 1rem !important;
        }

        .nav-link:hover {
            color: var(--primary-gold) !important;
        }

        /* ================= MOBILE BOTTOM BAR ================= */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: var(--white);
            display: flex;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.08);
            z-index: 1050;
            border-top: 1px solid rgba(0,0,0,0.05);
            padding-bottom: env(safe-area-inset-bottom);
        }

        .mobile-nav-item {
            text-decoration: none;
            color: #a0aec0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            flex: 1;
        }

        .mobile-nav-item i {
            font-size: 1.5rem;
            margin-bottom: 2px;
            transition: 0.3s;
        }

        .mobile-nav-item.active {
            color: var(--primary-gold);
        }

        .mobile-nav-item.active i {
            transform: translateY(-3px);
            color: var(--primary-gold);
        }

        /* Masquer/Afficher selon support */
        @media (min-width: 992px) {
            .mobile-bottom-nav { display: none; }
            body { padding-bottom: 0; }
        }

        /* Style Content */
        #content { 
            padding-top: 30px; 
            padding-bottom: 50px; 
            min-height: calc(100vh - 80px); 
        }

        .btn-logout { 
            color: var(--white); 
            background: rgba(255,255,255,0.1); 
            padding: 8px 18px; 
            border-radius: 8px; 
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .btn-logout:hover {
            background: #dc3545;
            color: white;
        }

        /* Badge or discret */
        .gold-border {
            border-left: 4px solid var(--primary-gold);
        }
    </style>
</head>

<body>

<div class="container-fluid p-0 d-none d-lg-block">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('individu.profile.show') }}">
                <i class="bi bi-shield-check me-2 text-primary-gold"></i>EPHD-CITOYEN
            </a>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('individu.dashboard') ? 'text-primary-gold' : '' }}" href="{{ route('individu.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('mes.classeurs') ? 'text-primary-gold' : '' }}" href="{{ route('mes.classeurs') }}">
                            <i class="bi bi-folder2-open me-1"></i> Mes classeurs
                        </a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <span class="text-white-50 small me-3">|</span>
                        <a href="{{ route('individu.profile.show') }}" class="text-white text-decoration-none me-3 fw-bold small">
                             {{ auth('individu')->user()->nom }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('auto-enregistrement.deconnexion') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-logout border-0"><i class="bi bi-power"></i></button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<div class="mobile-bottom-nav d-lg-none">
    <a href="{{ route('individu.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('individu.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-fill"></i>
        <span>Accueil</span>
    </a>
    <a href="{{ route('mes.classeurs') }}" class="mobile-nav-item {{ request()->routeIs('mes.classeurs') ? 'active' : '' }}">
        <i class="bi bi-archive-fill"></i>
        <span>Classeurs</span>
    </a>
    <a href="#" class="mobile-nav-item">
        <i class="bi bi-file-earmark-pdf-fill"></i>
        <span>Docs</span>
    </a>
    <a href="{{ route('individu.profile.show') }}" class="mobile-nav-item {{ request()->routeIs('individu.profile.show') ? 'active' : '' }}">
        <i class="bi bi-person-fill"></i>
        <span>Profil</span>
    </a>
</div>

<div id="content">
    <div class="container">
        @include('layouts.message')
        <div class="row">
            <div class="col-12">
                @yield('content')
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>