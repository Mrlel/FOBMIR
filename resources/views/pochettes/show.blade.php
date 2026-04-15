@extends('layouts.admin')

@section('title', 'Pochette - ' . $menage->nom_chef)

@section('extra_css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    :root {
        --soft-warning: #fff9db;
        --soft-success: #ebfbee;
        --soft-info: #e7f5ff;
        --dark-blue: #1e293b;
    }

    .card-hover {
        transition: all 0.3s cubic-bezier(.25,.8,.25,1);
        border-radius: 15px;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }

    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
        border-color: rgba(0,0,0,0.1) !important;
    }

    .bg-warning-soft { background-color: var(--soft-warning); }
    .bg-success-soft { background-color: var(--soft-success); }
    .bg-info-soft { background-color: var(--soft-info); }

    .icon-box {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
    }

    .breadcrumb-item + .breadcrumb-item::before { content: "›"; font-size: 1.2rem; vertical-align: middle; }
    
    .btn-rounded { border-radius: 10px; }
</style>
@endsection

@section('content')

    <div class="row mb-4">
        <div class="col-12">
    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-body p-4" style="background-color: #1e3a5f; color: white;">
            <div class="row align-items-center">
                
                <div class="col-md-8 py-4">
                    <!-- Title -->
                    <h2 class="h3 fw-bold mb-0 d-flex align-items-center text-white">
                        <span class="me-3 d-flex align-items-center justify-content-center"
                              style="width:45px;height:45px;border-radius:12px;background:#f59e0b;color:white;">
                            <i class="bi bi-folder2-open"></i>
                        </span>
                        {{ $pochette->libelle ?? 'Pochette de documents' }}
                    </h2>

                    <!-- Meta info -->
                    <div class="d-flex gap-3 mt-3">
                        <small class="text-white">
                            <i class="bi bi-person-badge me-1"></i>
                            Chef : <strong>{{ $menage->nom_chef }}</strong>
                        </small>
                        <small class="text-white">
                            <i class="bi bi-geo-alt me-1"></i>
                            {{ $menage->sousQuartier->nom ?? 'Quartier non défini' }}
                        </small>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="btn-group shadow-sm">
                        <a href="{{ route('menages.show', $menage) }}"
                           class="btn btn-light btn-rounded">
                            <i class="bi bi-arrow-left-short me-1"></i>Détails
                        </a>

                        @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'superadmin']))
                            <a href="{{ route('menages.pochette.edit', $menage) }}"
                               class="btn btn-warning text-white btn-rounded ms-2">
                                <i class="bi bi-pencil-square me-1"></i>Modifier
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card card-hover shadow-sm h-100 border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box bg-warning-soft text-warning">
                            <i class="bi bi-people-fill fs-2"></i>
                        </div>
                        <div class="ms-4">
                            <h5 class="fw-bold mb-1">Dossiers Individuels</h5>
                            <span class="badge rounded-pill bg-warning text-dark">
                                <i class="bi bi-person-check me-1"></i>{{ $menage->individus->count() }} Membres
                            </span>
                        </div>
                    </div>
                    <p class="text-muted mb-4">Centralisez les pièces d'identité, actes de naissance et diplômes pour chaque membre du foyer.</p>
                    <div class="d-grid">
                        <a href="{{ route('menages.dossiers.index', $menage) }}" class="btn text-white btn-lg shadow-sm btn-rounded py-3" style="background:#f59e0b;color:white;">
                            <i class="bi bi-folder-symlink me-2"></i>Accéder aux dossiers
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-hover shadow-sm h-100 border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box bg-success-soft text-success">
                            <i class="bi bi-collection-fill fs-2"></i>
                        </div>
                        <div class="ms-4">
                            <h5 class="fw-bold mb-1">Classeurs Collectifs</h5>
                            <span class="badge rounded-pill bg-success">
                                <i class="bi bi-files me-1"></i>Documents communs
                            </span>
                        </div>
                    </div>
                    <p class="text-muted mb-4">Gérez les documents partagés : contrats de bail, factures de service public et certificats familiaux.</p>
                    <div class="d-grid">
                        <a href="{{ route('menages.classeurs.index', $menage) }}" class="btn btn-success btn-lg shadow-sm btn-rounded py-3">
                            <i class="bi bi-layers me-2"></i>Accéder aux classeurs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection