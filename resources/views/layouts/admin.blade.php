<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JusticeFlow - Gestion Territoriale</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #f59e0b;
            --primary-dark: #d97706;
            --secondary: #1e3a5f;
            --dark: #0d1b2a;
            --light: #f1f5f9;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 85px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * { font-family: 'Inter', sans-serif; }
        body {min-height: 100vh; overflow-x: hidden; }

        /* --- SIDEBAR --- */
        .sidebar {
            height: 100vh;
            background: linear-gradient(180deg, var(--secondary) 0%, var(--dark) 100%);
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            z-index: 1050;
            transition: var(--transition);
            border-radius: 0 1.5rem 1.5rem 0;
            overflow-y: auto;
            overflow-x: hidden;
            padding-top: 1rem;
        }

        /* Scrollbar Webkit Discrète */
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { 
            background: rgba(255, 255, 255, 0.1); 
            border-radius: 10px; 
        }
        .sidebar::-webkit-scrollbar-thumb:hover { background: rgba(245, 158, 11, 0.4); }

        .sidebar-collapsed { width: var(--sidebar-collapsed-width); }

        /* --- NAVIGATION --- */
        .menu-section-title {
            padding: 1.5rem 1.5rem 0.5rem;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .nav-link {
            color: rgba(255,255,255,0.7) !important;
            padding: 0.8rem 1.2rem !important;
            margin: 0.2rem 1rem;
            border-radius: 10px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            white-space: nowrap;
        }
        
        .nav-link i { font-size: 1.2rem; min-width: 35px; }
        
        .nav-link:hover, .nav-link.active {
            background: rgba(245, 158, 11, 0.15) !important;
            color: var(--primary) !important;
        }

        .nav-link.active {
            background: var(--primary) !important;
            color: var(--dark) !important;
            font-weight: 600;
        }

        .has-dropdown::after {
            content: "\F282";
            font-family: "bootstrap-icons";
            margin-left: auto;
            transition: var(--transition);
            font-size: 0.8rem;
        }
        .has-dropdown:not(.collapsed)::after { transform: rotate(180deg); }

        .sub-menu {
            background: rgba(0,0,0,0.2);
            margin: 0 1rem;
            border-radius: 0 0 10px 10px;
            padding: 0.4rem 0;
        }

        .sidebar-collapsed .menu-section-title,
        .sidebar-collapsed .nav-link span,
        .sidebar-collapsed .has-dropdown::after,
        .sidebar-collapsed .sub-menu { display: none !important; }
        
        .sidebar-collapsed .nav-link { justify-content: center; margin: 0.2rem 0.5rem; }

        /* --- TOP NAVBAR --- */
        .top-navbar {
            background-color: #FFFFFF;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.6rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .user-profile-pill {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 5px 6px 5px 15px;
            background: white;
            border-radius: 50px;
            border: 1px solid #e2e8f0;
        }

        /* --- MAIN CONTENT --- */
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; transition: var(--transition); }
        .main-content-expanded { margin-left: var(--sidebar-collapsed-width); }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); border-radius: 0; }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
            .overlay.show { display: block; }
        }
    </style>
</head>
<body>

    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <nav class="sidebar" id="sidebar">
        <div class="nav flex-column pb-4">
            <div class="menu-section-title text-truncate">Principal</div>
            
            <a href="/admin/dashboard" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> <span>Tableau de bord</span>
            </a>

            @if (Auth::user()->role == 'superadmin')
            <a href="/admin/utilisateurs" class="nav-link {{ request()->is('admin/utilisateurs') ? 'active' : '' }}">
                <i class="bi bi-people"></i> <span>Points Focaux</span>
            </a>
         
            <a href="{{ route('admin.individus-menage.index') }}" class="nav-link {{ request()->is('admin/individus-menage*') ? 'active' : '' }}">
                <i class="bi bi-person-vcard-fill"></i> <span>Individus Ménage</span>
            </a>
            @endif

            @if (Auth::user()->role == 'point_focal')
            <a href="/individus" class="nav-link {{ request()->is('individus') || request()->is('individus/create') || request()->is('individus/*/edit') ? 'active' : '' }}">
                <i class="bi bi-person-vcard"></i> <span>Individus</span>
            </a>
            @endif

            <a href="/menages" class="nav-link {{ request()->is('menages*') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i> <span>Ménages</span>
            </a>

            <div class="menu-section-title text-truncate">Territoire</div>

            <div>
                <a href="#dropQuartiers" data-bs-toggle="collapse" class="nav-link has-dropdown {{ request()->is('quartiers*') || request()->is('sous-quartiers*') ? '' : 'collapsed' }}">
                    <i class="bi bi-geo-alt"></i> <span>Quartiers</span>
                </a>
                <div class="collapse {{ request()->is('quartiers*') || request()->is('sous-quartiers*') ? 'show' : '' }}" id="dropQuartiers">
                    <div class="sub-menu">
                        <a href="/quartiers" class="nav-link"><span>Liste des Quartiers</span></a>
                        <a href="/sous-quartiers" class="nav-link"><span>Sous-Quartiers</span></a>
                    </div>
                </div>
            </div>

            @if (Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin')
            <div>
                <a href="#dropChefs" data-bs-toggle="collapse" class="nav-link has-dropdown {{ request()->is('chef*') ? '' : 'collapsed' }}">
                    <i class="bi bi-person-badge-fill"></i> <span>Gestion des Chefs</span>
                </a>
                <div class="collapse {{ request()->is('chef*') ? 'show' : '' }}" id="dropChefs">
                    <div class="sub-menu">
                        <a href="/chefs-village" class="nav-link"><span>Chefs de Village</span></a>
                        <a href="/chefs-quartier" class="nav-link"><span>Chefs de Quartier</span></a>
                        <a href="/chef-sous-quartier" class="nav-link"><span>Chefs de Sous-Quartier</span></a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </nav>

    <div class="main-content" id="main-content">
        <header class="top-navbar">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light d-none d-lg-block" onclick="toggleSidebarDesktop()">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <button class="btn btn-light d-lg-none" onclick="toggleSidebar()">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-light border-0 rounded-3 position-relative">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    </button>
                    <button class="btn btn-light border-0 rounded-3">
                        <i class="bi bi-gear"></i>
                    </button>

                    <form action="{{ route('logout') }}" method="POST" class="mb-0">
                        @csrf
                        <button class="btn btn-light border-0 rounded-3 text-danger">
                            <i class="bi bi-box-arrow-right"></i> <span class="d-none d-md-inline">Deconnexion</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="p-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const overlay = document.getElementById('overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        function toggleSidebarDesktop() {
            sidebar.classList.toggle('sidebar-collapsed');
            mainContent.classList.toggle('main-content-expanded');
        }
    </script>
</body>
</html>