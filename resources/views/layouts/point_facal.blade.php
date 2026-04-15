<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    
    <!-- Stylesheets -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #0b1824ff;
             background-color: #2c3e50;
              background-color: #2c3e50;
              color: #333;
            --accent-color: #ffe75fff;
            --text-color: #ffffffff;
            --hover-color: #cccacaff;
            --transition-speed: 0.3s;
            --dropdown-animation: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
      
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color:  #f5f7fa;
            line-height: 1.5;
            overflow-x: hidden;
            color: #333;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* ============================
           SIDEBAR OPTIMISÉE
           ============================ */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary-color);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            transition: transform var(--transition-speed) ease;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .sidebar::-webkit-scrollbar {
            width: 1px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .nav-item {
            position: relative;
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--text-color);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: rgba(252, 204, 13, 0.1);
            transition: width 0.3s ease;
        }

        .nav-link:hover::before {
            width: 100%;
        }

        .nav-link:hover {
            color: var(--hover-color);
            border-left-color: var(--accent-color);
        }

        .nav-link.active {
            border-left-color: var(--accent-color);
            background: rgba(252, 204, 13, 0.1);
            color: var(--accent-color);
        }

        .nav-link i {
            margin-right: 1rem;
            font-size: 1.25rem;
            min-width: 24px;
            text-align: center;
            color: var(--accent-color);
            transition: transform 0.3s ease;
        }

        .nav-link:hover i {
            transform: scale(1.1);
        }

        /* ============================
           DROPDOWN MENUS - FLUIDITÉ AMÉLIORÉE
           ============================ */
        .nav-item.dropdown {
            position: relative;
        }

        .dropdown-toggle {
            position: relative;
            cursor: pointer;
        }

        .dropdown-toggle::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            border: none;
            margin-left: auto;
            margin-right: 0;
            transition: transform var(--dropdown-animation);
            font-size: 0.75rem;
        }

        .dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            position: static !important;
            transform: none !important;
            background: rgba(0, 0, 0, 0.3);
            border: none;
            border-radius: 0;
            padding: 0;
            margin: 0;
            width: 100%;
            box-shadow: none;
            border-left: 3px solid transparent;
            max-height: 0;
            overflow: hidden;
            transition: max-height var(--dropdown-animation), opacity var(--dropdown-animation);
            opacity: 0;
        }

        .dropdown-menu.show {
            max-height: 500px;
            opacity: 1;
            animation: dropdownSlideDown var(--dropdown-animation);
        }

        .dropdown-menu .dropdown-item {
            color: var(--text-color);
            padding: 0.65rem 1.5rem 0.65rem 3.5rem;
            font-size: 0.85rem;
            text-transform: uppercase;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .dropdown-menu .dropdown-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--accent-color);
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .dropdown-menu .dropdown-item:hover {
            background: rgba(252, 204, 13, 0.1);
            color: var(--accent-color);
            border-left-color: var(--accent-color);
            padding-left: 4rem;
        }

        .dropdown-menu .dropdown-item:hover::before {
            opacity: 1;
        }

        .dropdown-menu .dropdown-item i {
            font-size: 0.9rem;
            color: var(--accent-color);
            min-width: 20px;
        }

        /* Sous-dropdown pour gestion des chefs */
        .dropdown-menu .dropdown-menu {
            background: rgba(0, 0, 0, 0.4);
            margin-left: 0.5rem;
            max-height: 0;
        }

        .dropdown-menu .dropdown-menu.show {
            max-height: 300px;
        }

        .dropdown-menu .dropdown-menu .dropdown-item {
            padding-left: 4.5rem;
        }

        .dropdown-menu .dropdown-menu .dropdown-item:hover {
            padding-left: 5rem;
        }

        /* ============================
           SECTIONS ET DIVIDERS
           ============================ */
        .menu-section-title {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 1rem 1.5rem 0.5rem;
        
        }

        .menu-section-title:first-child {
            border-top: none;
            margin-top: 0;
        }

        /* Logout section */
        .logout-section {
            padding: 1rem 1.5rem;
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-form {
            width: 100%;
        }

        .logout-btn {
            width: 100%;
            padding: 0.75rem;
            background: rgba(241, 236, 236, 0.1);
            border: none;
            border-radius: 6px;
            color: var(--text-color);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .logout-btn:hover {
            background: rgba(252, 204, 13, 0.2);
            color: var(--accent-color);
            transform: translateY(-2px);
        }

        /* ============================
           ANIMATIONS
           ============================ */
        @keyframes dropdownSlideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============================
           RESPONSIVE DESIGN
           ============================ */
        .mobile-toggle {
            display: none;
            position: fixed;
            bottom: 80px;
            right: 20px;
            z-index: 1001;
            background: var(--primary-color);
            color: var(--accent-color);
            border: 2px solid var(--accent-color);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(26, 46, 53, 0.8);
            backdrop-filter: blur(4px);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        

        @media (max-width: 768px) {
            .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.8rem;
            }
            
            .dropdown-menu .dropdown-item {
                font-size: 0.8rem;
                padding: 0.6rem 1rem 0.6rem 3rem;
            }
        }

        /* ============================
           MAIN CONTENT
           ============================ */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition-speed) ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color:  #f5f7fa;
            padding: 1rem
        }

        .header {
            background-color: #1A2E35;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            flex-shrink: 0;
            border-radius: 6px;
        }
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 2px 0 20px rgba(0, 0, 0, 0.2);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .mobile-toggle {
                display: flex;
            }
            
            .dropdown-menu {
                position: absolute !important;
                left: 100%;
                top: 0;
                min-width: 220px;
                background: var(--primary-color);
                box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            }
            
            .dropdown-menu .dropdown-item {
                padding: 0.75rem 1.5rem;
            }
            
            .dropdown-menu .dropdown-menu {
                position: relative !important;
                left: 0;
                top: 0;
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Mobile Toggle -->
        <button class="mobile-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Overlay pour mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

        <!-- Sidebar Optimisée -->
        <nav class="sidebar" id="sidebar">  
            <p>{{Auth::user()->nom }}</p><p>{{Auth::user()->role }}</p>
            <div class="menu-section-title">PRINCIPAL</div> 
            <ul class="nav-menu">
                <li class="nav-item">
                    <a class="nav-link" href="/admin/dashboard">
                        <i class="bi bi-grid-1x2"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>
                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin')
                <li class="nav-item">
                    <a href="/admin/utilisateurs" class="nav-link">
                        <i class="bi bi-people"></i>
                        <span>Points Focaux</span>
                    </a>
                </li>
                @endif

                <li class="nav-item">
                    <a href="/individus" class="nav-link">
                        <i class="bi bi-person-vcard"></i>
                        <span>Individus</span>
                    </a>
                </li>
              
                <li class="nav-item">
                    <a href="/menages" class="nav-link">
                        <i class="bi bi-house-door"></i>
                        <span>Ménages</span>
                    </a>
                </li>

                <!-- Section Territoriale -->
                <div class="menu-section-title">TERRITOIRE</div>

                <!-- Menu Quartiers avec Sous-menus Optimisés -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="quartiersDropdown" 
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-geo-alt"></i>
                        <span>Quartiers</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="quartiersDropdown">
                        <!-- Sous-menu Liste -->
                        <li>
                            <a class="dropdown-item" href="/quartiers">
                                <i class="fas fa-list"></i>
                                Liste des Quartiers
                            </a>
                        </li>
                        
                        <!-- Sous-menu Sous-Quartiers -->
                        <li>
                            <a class="dropdown-item" href="/sous-quartiers">
                                <i class="fas fa-list-ol"></i>
                                Sous-Quartiers
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Menu Villages -->
                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="villagesDropdown" 
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                       <i class="fas fa-user-shield"></i>
                                Gestion des Chefs
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="villagesDropdown">
                                 <li class="nav-item dropdown">
                                    <a class="dropdown-item" href="/chefs-village">
                                        <i class="fas fa-user-shield"></i>
                                        Chefs de Village
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/chefs-quartier">
                                        <i class="fas fa-crown"></i>
                                        Chefs de Quartier
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/chef-sous-quartier">
                                        <i class="fas fa-user-tie"></i>
                                        Chefs de Sous-Quartier
                                    </a>
                                </li>
                    </ul>
                </li>
                @endif

            </ul>

            <!-- Section Déconnexion -->
            <div class="logout-section">
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf 
                    <button type="submit" class="logout-btn hover-lift">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Se déconnecter</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header" hidden>
                <a href="/dashboard" class="text-decoration-none">
                    <h3 class="main-title">
                        <span class="nom_cab text-white">Tableau de Bord</span>
                    </h3>
                </a>
                <div class="header-actions">
                    <a href="/profile" class="text-white text-decoration-none"><Span class="user"> <i class="bi bi-person-circle me-3"></i>{{ Auth::user()->nom}} -</Span></a>
                    <span  class="text-white text-decoration-none"> {{ Auth::user()->role}}</span>
                </div>
            </header>

            <!-- Content -->
            <div class="content">
                @include('layouts.message')
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // ============================
        // SIDEBAR MANAGEMENT
        // ============================
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
        }
        
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Fermer la sidebar en cliquant à l'extérieur sur mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 1024 && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target) && 
                sidebar.classList.contains('open')) {
                closeSidebar();
            }
        });

        // ============================
        // DROPDOWN OPTIMIZATIONS
        // ============================
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion fluide des dropdowns
            const dropdowns = document.querySelectorAll('.dropdown-toggle');
            
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    // Sur mobile, on empêche la propagation pour gérer manuellement
                    if (window.innerWidth <= 1024) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const menu = this.nextElementSibling;
                        if (menu) {
                            // Fermer les autres menus
                            document.querySelectorAll('.dropdown-menu.show').forEach(other => {
                                if (other !== menu) {
                                    other.classList.remove('show');
                                }
                            });
                            
                            // Basculer le menu courant
                            menu.classList.toggle('show');
                        }
                    }
                });
            });

            // Fermer les dropdowns en cliquant ailleurs
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown') && window.innerWidth > 1024) {
                    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                        menu.classList.remove('show');
                    });
                }
            });

            // Highlight des liens actifs
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link, .dropdown-item').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                    
                    // Ouvrir les dropdowns parents
                    let parent = link.closest('.dropdown-menu');
                    while (parent) {
                        parent.classList.add('show');
                        const toggle = parent.previousElementSibling;
                        if (toggle && toggle.classList.contains('dropdown-toggle')) {
                            toggle.setAttribute('aria-expanded', 'true');
                        }
                        parent = parent.parentElement.closest('.dropdown-menu');
                    }
                }
            });

            // Animation fluide pour les sous-menus
            const subDropdowns = document.querySelectorAll('.dropdown-menu .dropdown-toggle');
            subDropdowns.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    if (window.innerWidth > 1024) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const submenu = this.nextElementSibling;
                        if (submenu) {
                            submenu.classList.toggle('show');
                        }
                    }
                });
            });
        });

        // Ajustement responsive
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                closeSidebar();
                // Fermer tous les dropdowns sur desktop
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        // Animation de feedback sur les clics
        document.querySelectorAll('.nav-link, .dropdown-item').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 1024) {
                    setTimeout(closeSidebar, 200);
                }
                
                // Animation de feedback tactile
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    </script>
</body>
</html>