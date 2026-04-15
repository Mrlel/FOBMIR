@extends('layouts.admin')

@section('content')
<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --bg-light: #f4f7f6;
    }

    .bg-gradient-legal {
        background: linear-gradient(135deg, var(--secondary-blue) 0%, #2a357d 100%);
    }

    .stat-card {
        border: none;
        border-radius: 16px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .icon-shape {
        width: 48px;
        height: 48px;
        background: rgba(182, 140, 54, 0.1);
        color: var(--primary-gold);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quick-action-card {
        border: 2px solid transparent;
        transition: all 0.2s;
        cursor: pointer;
    }

    .quick-action-card:hover {
        border-color: var(--primary-gold);
        background-color: #fffdfa;
    }

    .text-gold { color: var(--primary-gold) !important; }
</style>

<div class="p-4">
    <header class="card bg-gradient-legal text-white mb-4 border-0 shadow-lg overflow-hidden">
        <div class="card-body p-4 position-relative">
            <div class="position-absolute end-0 top-0 opacity-10 p-4">
                <i class="bi bi-shield-check display-1"></i>
            </div>
            
            <div class="row align-items-center position-relative" style="z-index: 2;">
                <div class="col-md-8">
                    <h2 class="display-6 fw-bold mb-1">
                        Bonjour, {{ auth()->user()->prenom }} <span class="fs-4 fw-normal opacity-75">👋</span>
                    </h2>
                    <p class="lead mb-0 opacity-75">
                        <i class="bi bi-calendar3 me-2"></i>{{ now()->translatedFormat('l d F Y') }}.
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-white text-dark p-2 px-3 rounded-pill shadow-sm">
                        <i class="bi bi-person-badge text-gold me-2"></i>{{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
            </div>
        </div>
    </header>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <h5 class="fw-bold mb-3" style="color: var(--secondary-blue);">Actions Rapides</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <a href="{{ route('menages.create') }}" class="text-decoration-none">
                        <div class="card quick-action-card shadow-sm p-3 text-center">
                            <i class="bi bi-house-add fs-2 text-gold mb-2"></i>
                            <div class="fw-bold text-dark">Nouveau Ménage</div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('menages.index') }}" class="text-decoration-none">
                        <div class="card quick-action-card shadow-sm p-3 text-center">
                            <i class="bi bi-search fs-2 text-gold mb-2"></i>
                            <div class="fw-bold text-dark">Rechercher</div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <div class="card quick-action-card shadow-sm p-3 text-center">
                        <i class="bi bi-graph-up-arrow fs-2 text-gold mb-2"></i>
                        <div class="fw-bold text-dark">Rapports</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card quick-action-card shadow-sm p-3 text-center">
                        <i class="bi bi-gear fs-2 text-gold mb-2"></i>
                        <div class="fw-bold text-dark">Paramètres</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection