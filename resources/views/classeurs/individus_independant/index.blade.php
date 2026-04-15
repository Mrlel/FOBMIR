@extends('layouts.individu')

@section('content')

<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --glass-bg: rgba(255, 255, 255, 0.7);
    }

    /* Stats Cards - Frosted Glass */
    .stat-card-glass {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 15px;
        transition: transform 0.3s ease;
    }

    /* Folder Card Style */
    .folder-card {
        border: none;
        border-radius: 9px;
        background: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .folder-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(23, 30, 76, 0.1);
    }

    .folder-icon-bg {
        width: 50px;
        height: 50px;
        background: rgba(182, 140, 54, 0.1);
        color: var(--primary-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
    }

    .btn-gold-outline {
        color: var(--primary-gold);
        border: 1px solid var(--primary-gold);
        border-radius: 8px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-gold-outline:hover {
        background: var(--primary-gold);
        color: white;
    }

    .btn-blue {
        background: var(--secondary-blue);
        color: white;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-blue:hover {
        background: #252f6b;
        color: white;
    }

    .folder-badge {
        font-size: 0.7rem;
        background: var(--secondary-blue);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<div class="container-fluid py-2">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-archive-fill text-primary-gold me-2"></i>Mes classeurs
            </h3>
            <p class="text-muted mb-0 small">Dossier de référence : <span class="fw-bold text-secondary">{{ $dossier->nom }}</span></p>
        </div>
        <a href="{{ route('individu.classeurs.create') }}" class="btn btn-blue px-4 shadow-sm">
            <i class="bi bi-folder-plus me-2"></i>Nouveau classeur
        </a>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-4 col-6">
            <div class="stat-card-glass p-3 shadow-sm">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Classeurs</div>
                        <div class="h3 fw-bold mb-0 text-dark">{{ $classeurs->count() }}</div>
                    </div>
                    <div class="folder-icon-bg">
                        <i class="bi bi-collection"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-6">
            <div class="stat-card-glass p-3 shadow-sm">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Documents</div>
                        <div class="h3 fw-bold mb-0 text-dark">{{ $classeurs->sum('documents_count') }}</div>
                    </div>
                    <div class="folder-icon-bg" style="background: rgba(23, 30, 76, 0.05); color: var(--secondary-blue);">
                        <i class="bi bi-files"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($classeurs as $classeur)
            <div class="col-md-6 col-lg-4">
                <div class="card folder-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="folder-icon-bg">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                        <span class="folder-badge">Classeur</span>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1">{{ $classeur->theme }}</h5>
                    <div class="d-flex align-items-center text-muted small mb-3">
                        <i class="bi bi-file-earmark-check me-1"></i>
                        {{ $classeur->documents_count }} document(s) archivé(s)
                    </div>

                    <p class="text-muted small mb-4 flex-grow-1">
                        {{ \Illuminate\Support\Str::limit($classeur->description ?? 'Aucune description fournie pour ce classeur.', 85) }}
                    </p>

                    <div class="d-grid gap-2 d-md-flex mt-auto">
                        <a href="{{ route('individu.classeurs.show', $classeur) }}" class="btn btn-blue btn-sm px-3 flex-fill">
                            <i class="bi bi-eye me-1"></i> Ouvrir
                        </a>
                        <a href="{{ route('individu.classeurs.edit', $classeur) }}" class="btn btn-gold-outline btn-sm px-3 flex-fill">
                            <i class="bi bi-pencil me-1"></i> Gérer
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-folder-x display-1 text-light"></i>
                </div>
                <h5 class="fw-bold text-dark">Votre espace est vide</h5>
                <p class="text-muted mb-4">Créez votre premier classeur pour commencer à numériser vos documents.</p>
                <a href="{{ route('individu.classeurs.create') }}" class="btn btn-blue px-5">
                    <i class="bi bi-plus-lg me-2"></i>Créer mon premier classeur
                </a>
            </div>
        @endforelse
    </div>
</div>

@endsection