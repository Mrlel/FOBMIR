@extends('layouts.individu')

@section('title', 'Mon espace personnel')

@section('content')

<style>
    /* Variables locales alignées sur le layout KindFlow */
    :root {
        --teal-gradient: linear-gradient(135deg, #0E3D38 0%, #1B5E57 100%);
        --orange-action: #F06A1D;
        --card-border: #e8e2da;
    }

    /* Header Welcome Card */
    .welcome-card {
        background: var(--teal-gradient);
        border: none;
        border-radius: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(27, 94, 87, 0.15);
    }

    /* Décoration subtile en arrière-plan (Cercle Orange) */
    .welcome-card::after {
        content: "";
        position: absolute;
        top: -40px;
        right: -40px;
        width: 180px;
        height: 180px;
        background: var(--orange-action);
        opacity: 0.1;
        border-radius: 50%;
    }

    /* Icônes d'action */
    .stat-icon {
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        font-size: 1.6rem;
        transition: all 0.3s ease;
    }

    /* Cartes d'actions rapides */
    .quick-action-card {
        background: var(--warm-white);
        border: 1.5px solid var(--card-border);
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .quick-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.05);
        border-color: var(--orange);
    }

    .quick-action-card:hover .stat-icon {
        transform: scale(1.1);
    }

    /* Typography fixes */
    .welcome-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 1.8rem;
    }

    .section-label {
        font-weight: 700;
        color: var(--teal-dark);
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-size: 0.8rem;
    }
</style>

<div class="container-fluid py-3">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card welcome-card text-white p-4">
                <div class="card-body p-0">
                    <div class="row align-items-center">
                        <div class="col-8 col-md-9">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-white bg-opacity-20 text-black px-3 py-2 rounded-pill small">
                                    <i class="bi bi-calendar3 me-2"></i> {{ now()->translatedFormat('d F Y') }}
                                </span>
                            </div>
                            <h2 class="welcome-title mb-2">
                                Bonjour, {{ $individu->prenom }} ! 
                            </h2>
                            <p class="mb-0 opacity-75 small">
                                <i class="bi bi-shield-lock-fill me-1 text-warning"></i> 
                                Vos archives administratives sont sécurisées.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="px-1 mb-3 d-flex align-items-center justify-content-between">
        <span class="section-label">Actions prioritaires</span>
        <i class="bi bi-three-dots text-muted"></i>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <a href="{{ route('mes.classeurs') }}" class="text-decoration-none">
                <div class="card quick-action-card h-100 p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3" style="background: rgba(240, 106, 29, 0.1); color: var(--orange);">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">Mes Classeurs</h6>
                            <p class="text-muted small mb-0">Consulter mes pièces archivées</p>
                        </div>
                        <div class="ms-auto bg-light rounded-circle p-2">
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6">
            <a href="{{ route('individu.profile.show') }}" class="text-decoration-none">
                <div class="card quick-action-card h-100 p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3" style="background: rgba(27, 94, 87, 0.1); color: var(--teal);">
                            <i class="bi bi-person-vcard"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">Identité Civile</h6>
                            <p class="text-muted small mb-0">Gérer mes informations personnelles</p>
                        </div>
                        <div class="ms-auto bg-light rounded-circle p-2">
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 mt-4">
            <div class="p-4 border-dashed rounded-4 text-center bg-white border border-2" style="border-style: dashed !important; border-color: #d1d5db !important;">
                <div class="mb-3">
                    <i class="bi bi-cloud-arrow-up h1 text-muted opacity-50"></i>
                </div>
                <h6 class="fw-bold">Besoin d'ajouter une pièce ?</h6>
                <p class="small text-muted">Numérisez vos actes de naissance ou titres fonciers instantanément.</p>
                <button class="btn px-4 py-2 text-white shadow-sm" style="background: var(--teal); border-radius: 12px; font-weight: 600;">
                    Scanner un document
                </button>
            </div>
        </div>
    </div>
</div>

@endsection