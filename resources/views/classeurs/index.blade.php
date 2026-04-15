@extends('layouts.admin')

@section('title', 'Classeurs - ' . $menage->nom_chef)

@section('content')

<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --dark-blue: #0d1231;
    }

    .header-section {
        background: white;
        padding: 1.5rem;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    /* Cards Statut */
    .card-stat {
        border: none;
        border-radius: 6px;
        transition: transform 0.2s;
    }

    .card-stat-main {
        background: linear-gradient(135deg, var(--secondary-blue) 0%, var(--dark-blue) 100%);
        color: white;
    }

    .icon-box-gold {
        background: rgba(182, 140, 54, 0.2);
        color: var(--primary-gold);
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }

    /* Classeur Cards */
    .classeur-card {
        border: none;
        border-radius: 15px;
        background: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border-top: 4px solid transparent;
    }

    .classeur-card:hover {
        transform: translateY(-5px);
        border-top-color: var(--primary-gold);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .folder-icon-bg {
        background: #f8f9fa;
        color: var(--primary-gold);
        border-radius: 12px;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-gold {
        background-color: var(--primary-gold);
        color: white;
        border: none;
    }

    .btn-gold:hover {
        background-color: #96722c;
        color: white;
    }

    .btn-outline-blue {
        border: 1px solid var(--secondary-blue);
        color: var(--secondary-blue);
    }

    .btn-outline-blue:hover {
        background: var(--secondary-blue);
        color: white;
    }

    .badge-count {
        background: var(--secondary-blue);
        color: white;
        font-weight: 500;
        padding: 0.5em 0.8em;
    }
</style>

<div class="container-fluid py-4">
    <div class="header-section d-flex flex-wrap justify-content-between align-items-center mb-4 shadow-sm">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Ménages</a></li>
                    <li class="breadcrumb-item active text-primary-gold">{{ $menage->nom_chef }}</li>
                </ol>
            </nav>
            <h2 class="h4 fw-bold text-dark mb-0">
                <i class="bi bi-folder2-open me-2 text-primary-gold"></i>Classeurs thématiques
            </h2>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <a href="{{ route('menages.pochette.show', $menage) }}" class="btn btn-light border btn-sm px-3">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
            @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'super_admin']))
                <a href="{{ route('menages.classeurs.create', $menage) }}" class="btn btn-gold btn-sm px-3 shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Nouveau classeur
                </a>
            @endif
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-stat card-stat-main shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box-gold me-3">
                        <i class="bi bi-person-badge fs-4"></i>
                    </div>
                    <div>
                        <p class="mb-0 small text-uppercase opacity-75 fw-bold" style="letter-spacing: 0.5px;">Chef de ménage</p>
                        <h5 class="mb-0 fw-bold">{{ $menage->nom_chef }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat h-100" style="border: 1px solid var(--secondary-blue)">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box-gold me-3" style="background: rgba(23, 30, 76, 0.05); color: var(--secondary-blue);">
                        <i class="bi bi-archive fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small text-uppercase fw-bold">Pochette de référence</p>
                        <h5 class="mb-0 fw-bold text-dark">{{ $pochette->libelle }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-stat ext-center h-100" style="border: 1px solid var(--secondary-blue)">
                <div class="card-body d-flex flex-column justify-content-center">
                    <p class="text-muted mb-0 small fw-bold">Classeurs</p>
                    <h3 class="mb-0 fw-bold" style="color: var(--primary-gold)">{{ $classeurs->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-statt ext-center h-100" style="border: 1px solid var(--secondary-blue)">
                <div class="card-body d-flex flex-column justify-content-center">
                    <p class="text-muted mb-0 small fw-bold">Total Docs</p>
                    <h3 class="mb-0 fw-bold" style="color: var(--secondary-blue)">{{ $classeurs->sum('documents_count') }}</h3>
                </div>
            </div>
        </div>
    </div>

    @if($classeurs->count() > 0)
        <div class="row g-4">
            @foreach($classeurs as $classeur)
                <div class="col-md-6 col-lg-4">
                    <div class="card classeur-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div class="folder-icon-bg shadow-sm">
                                    <i class="bi bi-folder-fill fs-3"></i>
                                </div>
                                <span class="badge rounded-pill badge-count shadow-sm">
                                    {{ $classeur->documents_count }} document(s)
                                </span>
                            </div>
                            
                            <h5 class="fw-bold text-dark mb-2">{{ $classeur->theme }}</h5>
                            <p class="text-muted small mb-0" style="height: 45px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                {{ $classeur->description ?? 'Aucune description spécifiée pour ce classeur.' }}
                            </p>
                            
                            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-clock-history me-1"></i> {{ $classeur->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pb-4 px-4">
                            <div class="row g-2">
                                <div class="col-12">
                                    <a href="{{ route('menages.classeurs.show', [$menage, $classeur]) }}" class="btn btn-outline-blue w-100 fw-bold">
                                        <i class="bi bi-eye me-2"></i>Consulter le classeur
                                    </a>
                                </div>
                                @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'super_admin']))
                                    <div class="col-6">
                                        <a href="{{ route('menages.classeurs.edit', [$menage, $classeur]) }}" class="btn btn-light border w-100 btn-sm text-muted">
                                            <i class="bi bi-pencil me-1"></i> Éditer
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('menages.classeurs.documents.create', [$menage, $classeur]) }}" class="btn btn-gold w-100 btn-sm fw-bold">
                                            <i class="bi bi-plus-lg me-1"></i> Ajouter
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 shadow-sm py-5 mt-4">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-folder-x display-1 text-light"></i>
                </div>
                <h4 class="fw-bold text-dark">Aucun classeur pour ce ménage</h4>
                <p class="text-muted mx-auto mb-4" style="max-width: 450px;">
                    L'organisation par classeur permet de trier les documents numérisés par thématique (Santé, Éducation, Identité) pour une recherche plus rapide.
                </p>
                @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'super_admin']))
                    <a href="{{ route('menages.classeurs.create', $menage) }}" class="btn btn-gold px-5 py-2 shadow-sm fw-bold">
                        <i class="bi bi-folder-plus me-2"></i>Créer le premier classeur
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection