@extends('layouts.admin')

@section('title', $dossier->nom . ' - ' . $menage->nom_chef)

@section('content')

<style>
    :root {
        --primary-gold: #b68c36;
        --primary-gold-dark: #96722c;
        --secondary-blue: #171e4c;
        --dark-blue: #0d1231;
        --light-bg: #f8fafc;
    }

    /* Breadcrumb */
    .breadcrumb-item a {
        color: var(--secondary-blue);
        text-decoration: none;
        font-weight: 500;
    }

    .breadcrumb-item.active {
        color: var(--primary-gold);
        font-weight: 600;
    }

    /* Buttons */
    .btn-gold-custom {
        background-color: var(--primary-gold);
        border: none;
        color: #fff;
        transition: all 0.3s;
    }

    .btn-gold-custom:hover {
        background-color: var(--primary-gold-dark);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-outline-blue {
        border: 1px solid var(--secondary-blue);
        color: var(--secondary-blue);
        transition: all 0.3s;
    }

    .btn-outline-blue:hover {
        background-color: var(--secondary-blue);
        color: #fff;
    }

    /* Cards */
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .card-header-blue {
        background-color: var(--secondary-blue);
        color: #fff;
        border-radius: 12px 12px 0 0 !important;
    }

    .card-stat-gold {
        background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-dark));
        color: #fff;
        border: none;
        border-radius: 12px;
    }

    .card-stat-blue {
        background: linear-gradient(135deg, var(--secondary-blue), var(--dark-blue));
        color: #fff;
        border: none;
        border-radius: 12px;
    }

    /* Classeur cards */
    .classeur-card {
        transition: all 0.3s ease;
        border: 1px solid #edf2f7;
    }

    .classeur-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        border-color: var(--primary-gold);
    }

    .badge-gold-soft {
        background-color: rgba(182, 140, 54, 0.1);
        color: var(--primary-gold-dark);
        font-weight: 600;
        border-radius: 6px;
    }

    .section-title {
        color: var(--secondary-blue);
        font-weight: 800;
        letter-spacing: 0.5px;
        text-uppercase;
        font-size: 0.9rem;
    }
</style>

<div class="container-fluid py-4">

    <div class="row mb-4 align-items-center">
        <div class="col-md-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('menages.index') }}">Ménages</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('menages.show', $menage) }}">{{ $menage->nom_chef }}</a></li>
                    <li class="breadcrumb-item active">Dossier Individuel</li>
                </ol>
            </nav>

            <h2 class="h3 fw-bold mb-0" style="color: var(--secondary-blue)">
                <i class="bi bi-folder2-open me-2" style="color: var(--primary-gold)"></i>
                {{ $dossier->nom }}
            </h2>
        </div>

        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                <a href="{{ route('menages.dossiers.index', $menage) }}" class="btn btn-white border-end btn-sm">
                    <i class="bi bi-chevron-left me-1"></i> Liste
                </a>

                @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'superadmin']))
                    <a href="{{ route('menages.dossiers.edit', [$menage, $dossier]) }}" class="btn btn-white border-end btn-sm text-primary">
                        <i class="bi bi-pencil-square me-1"></i> Modifier
                    </a>

                    <a href="{{ route('menages.dossiers.classeurs.create', [$menage, $dossier]) }}" class="btn btn-gold-custom btn-sm px-3">
                        <i class="bi bi-plus-lg me-1"></i> Nouveau classeur
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card card-custom h-100 bg-white">
                <div class="card-header card-header-blue py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>Détails du dossier</h6>
                </div>

                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="text-uppercase small fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 1px;">Propriétaire</label>
                            <div class="d-flex align-items-center mt-2">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                    <i class="bi bi-person fs-4 text-dark"></i>
                                </div>
                                <div class="fw-bold fs-5" style="color: var(--secondary-blue)">
                                    {{ $dossier->individuMenage->nom }} {{ $dossier->individuMenage->prenom }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4 text-md-end">
                            <label class="text-uppercase small fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 1px;">Référence Ménage</label>
                            <div class="mt-2">
                                <span class="badge bg-light text-dark border p-2 px-3" style="border-radius: 8px;">
                                    <i class="bi bi-house-door me-1"></i> Chef : {{ $menage->nom_chef }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12">
                            <hr class="opacity-10">
                            <label class="text-uppercase small fw-bold text-muted mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">Description</label>
                            <p class="text-dark leading-relaxed">
                                {{ $dossier->description ?: 'Aucune description fournie.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent border-top-0 pb-3 px-4 text-end">
                    <small class="text-muted italic">
                        <i class="bi bi-calendar3 me-1"></i> Dossier initialisé le {{ $dossier->created_at->format('d/m/Y') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="row g-3">
                <div class="col-12">
                    <div class="card card-stat-gold shadow-sm">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0 fw-bold">{{ $classeurs->count() }}</h2>
                                <p class="mb-0 small opacity-75 fw-medium text-uppercase">Classeurs actifs</p>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded p-3">
                                <i class="bi bi-collection fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card card-stat-blue shadow-sm">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0 fw-bold">
                                    {{ $classeurs->sum('documents_count') }}
                                </h2>
                                <p class="mb-0 small opacity-75 fw-medium text-uppercase">Documents archivés</p>
                            </div>
                            <div class="bg-white bg-opacity-10 rounded p-3">
                                <i class="bi bi-file-earmark-zip fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12 mb-4 d-flex align-items-center">
            <h5 class="section-title mb-0">
                <i class="bi bi-grid-3x3-gap-fill me-2" style="color: var(--primary-gold)"></i>
                Contenu du dossier
            </h5>
            <div class="flex-grow-1 ms-3 hr-line" style="height: 1px; background: #e2e8f0;"></div>
        </div>

        @forelse($classeurs as $classeur)
            <div class="col-md-6 col-xl-4 mb-4">
                <div class="card classeur-card card-custom h-100 bg-white">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-2 rounded bg-light" style="color: var(--secondary-blue)">
                                <i class="bi bi-folder-fill fs-3"></i>
                            </div>
                            <span class="badge badge-gold-soft py-2 px-3">
                                <i class="bi bi-files me-1"></i> {{ $classeur->documents_count }}
                            </span>
                        </div>

                        <h6 class="fw-bold mb-2 text-dark">
                            {{ $classeur->theme }}
                        </h6>

                        <p class="text-muted small mb-4" style="height: 40px; overflow: hidden;">
                            {{ Str::limit($classeur->description, 85) ?: 'Pas de description détaillée.' }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('menages.dossiers.classeurs.show', [$menage, $dossier, $classeur]) }}"
                               class="btn btn-sm btn-outline-blue px-4" style="border-radius: 6px;">
                                <i class="bi bi-eye me-1"></i> Ouvrir
                            </a>

                            @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'super_admin']))
                                <a href="{{ route('menages.classeurs.documents.create', [$menage, $classeur]) }}"
                                   class="btn btn-sm text-decoration-none fw-bold p-0"
                                   style="color: var(--primary-gold)">
                                    <i class="bi bi-plus-circle-fill me-1"></i> Document
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 border-dashed rounded-3" style="border: 2px dashed #cbd5e0;">
                <div class="mb-3 opacity-25">
                    <i class="bi bi-folder-x" style="font-size: 4rem; color: var(--secondary-blue);"></i>
                </div>
                <h5 class="fw-bold" style="color: var(--secondary-blue)">Dossier vide</h5>
                <p class="text-muted small">Aucun classeur thématique n'a encore été créé pour cet individu.</p>

                @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'super_admin']))
                    <a href="{{ route('menages.dossiers.classeurs.create', [$menage, $dossier]) }}"
                       class="btn btn-gold-custom px-4 mt-2 shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Créer le premier classeur
                    </a>
                @endif
            </div>
        @endforelse
    </div>

</div>

@endsection