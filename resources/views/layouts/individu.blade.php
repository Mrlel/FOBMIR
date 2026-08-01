<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyPapyrus – @yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <style>
        :root {
            --teal: #1B5E57;
            --teal-dark: #0E3D38;
            --orange: #F06A1D;
            --orange-light: #FF8C42;
            --cream: #FBF7F2;
            --warm-white: #FFFDF9;
            --text-dark: #1A1A1A;
            --text-muted: #6B7280;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--cream);
            color: var(--text-dark);
            margin: 0;
            padding-bottom: 80px; 
        }

        /* ================= NAVBAR DESKTOP ================= */
        .navbar {
            background: var(--teal-dark);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 0.6rem 0;
            border-bottom: 2px solid var(--orange);
            border-radius: 0 0 11px 11px;
            max-width: 1100px;
            margin : auto;

        }

        .navbar-brand img {
            height: 45px;
            border-radius: 8px;
            background: white;
            padding: 2px;
        }

        .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            transition: 0.3s;
            margin: 0 5px;
            border-radius: 8px;
        }

        .nav-link:hover, .nav-link.active {
            color: white !important;
            background: rgba(255,255,255,0.1);
        }

        .nav-link.active {
            border-bottom: 2px solid var(--orange-light);
            border-radius: 8px 8px 0 0;
        }

        /* ================= MOBILE BOTTOM BAR ================= */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 75px;
            background: var(--warm-white);
            display: flex;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -8px 25px rgba(0,0,0,0.06);
            z-index: 1050;
            border-top: 1px solid rgba(0,0,0,0.05);
            padding-bottom: env(safe-area-inset-bottom);
        }

        .mobile-nav-item {
            text-decoration: none;
            color: #94A3B8;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            flex: 1;
        }

        .mobile-nav-item i {
            font-size: 1.4rem;
            margin-bottom: 4px;
            transition: 0.3s;
        }

        .mobile-nav-item.active {
            color: var(--teal);
        }

        .mobile-nav-item.active i {
            color: var(--orange);
            transform: translateY(-2px);
        }

        /* Responsive Adjustments */
        @media (min-width: 992px) {
            .mobile-bottom-nav { display: none; }
            body { padding-bottom: 0; }
        }

        /* Content Area */
        #content { 
            padding-top: 2rem; 
            padding-bottom: 4rem; 
            min-height: calc(100vh - 150px); 
        }

        .btn-logout { 
            background: var(--orange);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 10px;
            transition: 0.3s;
        }

        .btn-logout:hover {
            background: #d45917;
            transform: scale(1.05);
        }

        /* Éléments de style globaux pour les vues enfants */
        .card-custom {
            background: var(--warm-white);
            border: 1.5px solid #e8e2da;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }
        
        .gold-border { border-left: 5px solid var(--orange); }
    </style>
</head>

<body>

<div class="container-fluid p-0 d-none d-lg-block">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('individu.dashboard') }}">
                <img src="/logo_p.jpeg" height="50" alt="Logo" class="me-2">
            </a>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('individu.dashboard') ? 'active' : '' }}" href="{{ route('individu.dashboard') }}">
                            <i class="bi bi-grid-1x2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('mes.classeurs') ? 'active' : '' }}" href="{{ route('mes.classeurs') }}">
                            <i class="bi bi-archive me-1"></i> Mes classeurs
                        </a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <div class="d-flex align-items-center bg-white bg-opacity-10 py-1 px-3 rounded-pill">
                            <i class="bi bi-person-circle text-orange-light me-2"></i>
                            <a href="{{ route('individu.profile.show') }}" class="text-white text-decoration-none fw-bold small">
                                 {{ auth('individu')->user()->prenom }} {{ auth('individu')->user()->nom }}
                            </a>
                        </div>
                    </li>
                    <li class="nav-item ms-3">
                        <form action="{{ route('auto-enregistrement.deconnexion') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-logout" title="Déconnexion">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<div class="mobile-bottom-nav d-lg-none">
    <a href="{{ route('individu.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('individu.dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-door{{ request()->routeIs('individu.dashboard') ? '-fill' : '' }}"></i>
        <span>Accueil</span>
    </a>
    <a href="{{ route('mes.classeurs') }}" class="mobile-nav-item {{ request()->routeIs('mes.classeurs') ? 'active' : '' }}">
        <i class="bi bi-folder{{ request()->routeIs('mes.classeurs') ? '-fill' : '' }}"></i>
        <span>Archives</span>
    </a>
    <a href="#" class="mobile-nav-item">
        <div style="width:45px; height:45px; background:var(--orange); border-radius:50%; display:flex; align-items:center; justify-content:center; margin-top:-30px; border:5px solid var(--cream); color:white; box-shadow:0 4px 10px rgba(240,106,29,0.3);">
            <i class="bi bi-plus-lg m-0"></i>
        </div>
        <span style="margin-top:2px;">Ajouter</span>
    </a>
    <a href="{{ route('individu.profile.show') }}" class="mobile-nav-item {{ request()->routeIs('individu.profile.show') ? 'active' : '' }}">
        <i class="bi bi-person{{ request()->routeIs('individu.profile.show') ? '-fill' : '' }}"></i>
        <span>Profil</span>
    </a>
</div>

<div id="content">
    <div class="container">
        @if(session('success') || session('error'))
            <div class="row">
                <div class="col-12 mb-3">
                    @include('layouts.message')
                </div>
            </div>
        @endif

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