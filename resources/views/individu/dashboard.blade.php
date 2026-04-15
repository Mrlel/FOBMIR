@extends('layouts.individu')

@section('title', 'Mon espace personnel')

@section('content')

<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.2);
    }

    /* Header avec effet Frosted Glass */
    .welcome-card {
        background: linear-gradient(135deg, var(--secondary-blue) 0%, #252f6b 100%);
        border: none;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(23, 30, 76, 0.2);
    }

    /* Décoration Or en arrière-plan */
    .welcome-card::after {
        content: "";
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: var(--primary-gold);
        opacity: 0.1;
        border-radius: 50%;
    }

    .glass-effect {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 15px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: var(--light-gold);
        color: var(--primary-gold);
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .quick-action-card {
        transition: all 0.3s ease;
        border: 1px solid #edf2f7;
        cursor: pointer;
    }

    .quick-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.05);
        border-color: var(--primary-gold);
    }
</style>

<div class="container-fluid py-2">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card welcome-card text-white p-4">
                <div class="card-body p-0">
                    <div class="row align-items-center">
                        <div class="col-8 col-md-9">
                            <span class="badge bg-white bg-opacity-25 text-white mb-2 px-3 py-2 rounded-pill small">
                                <i class="bi bi-calendar3 me-1"></i> {{ now()->translatedFormat('d F Y') }}
                            </span>
                            <h2 class="fw-bold mb-1">
                                Bonjour, {{ $individu->prenom }} ! 
                            </h2>
                            <p class="mb-0 opacity-75 small">
                                <i class="bi bi-shield-check me-1"></i> Votre espace sécurisé est à jour.
                            </p>
                        </div>
                        <div class="col-4 col-md-3 text-end">
                            <div class="d-inline-block p-2 rounded-circle bg-white bg-opacity-10">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($individu->nom) }}&background=b68c36&color=fff&size=128" 
                                     class="rounded-circle shadow-sm" width="70" alt="Avatar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card glass-effect h-100 p-3 text-center border-0 shadow-sm">
                <div class="text-primary-gold mb-1"><i class="bi bi-folder-fill fs-3"></i></div>
                <div class="h4 fw-bold mb-0 text-dark">4</div>
                <div class="text-muted extra-small">Classeurs</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card glass-effect h-100 p-3 text-center border-0 shadow-sm">
                <div class="text-primary-gold mb-1"><i class="bi bi-file-earmark-text-fill fs-3"></i></div>
                <div class="h4 fw-bold mb-0 text-dark">12</div>
                <div class="text-muted extra-small">Documents</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card glass-effect h-100 p-3 text-center border-0 shadow-sm">
                <div class="text-success mb-1"><i class="bi bi-check-circle-fill fs-3"></i></div>
                <div class="h4 fw-bold mb-0 text-dark">Vérifié</div>
                <div class="text-muted extra-small">État Profil</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card glass-effect h-100 p-3 text-center border-0 shadow-sm">
                <div class="text-info mb-1"><i class="bi bi-bell-fill fs-3"></i></div>
                <div class="h4 fw-bold mb-0 text-dark">2</div>
                <div class="text-muted extra-small">Alertes</div>
            </div>
        </div>
    </div>

    <h6 class="fw-bold mb-3 text-dark px-1">Actions rapides</h6>
    <div class="row g-3">
        <div class="col-md-6">
            <a href="{{ route('mes.classeurs') }}" class="text-decoration-none">
                <div class="card quick-action-card h-100 p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3" style="background: rgba(182, 140, 54, 0.1);">
                            <i class="bi bi-journal-bookmark"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">Consulter mes classeurs</h6>
                            <p class="text-muted small mb-0">Accédez à vos pièces numérisées</p>
                        </div>
                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('individu.profile.show') }}" class="text-decoration-none">
                <div class="card quick-action-card h-100 p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3" style="background: rgba(23, 30, 76, 0.05); color: var(--secondary-blue);">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">Mon Profil Civil</h6>
                            <h4 class="text-muted small mb-0">Vérifiez vos informations</h4>
                        </div>
                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection