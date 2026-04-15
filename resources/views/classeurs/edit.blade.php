@extends('layouts.admin')

@section('title', 'Modifier le classeur - ' . $classeur->theme)

@section('content')

<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --danger-soft: #fff5f5;
    }

    .edit-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }

    .edit-header {
        background: white;
        border-bottom: 2px solid #f8f9fa;
        padding: 1.5rem;
    }

    .form-label-custom {
        font-weight: 700;
        color: var(--secondary-blue);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Thème verrouillé */
    .locked-field {
        background-color: #fcfcfc !important;
        border: 1px dashed #dee2e6 !important;
        color: #6c757d;
        font-weight: 600;
    }

    .btn-gold-save {
        background-color: var(--primary-gold);
        color: white;
        border: none;
        font-weight: 700;
        padding: 0.7rem 1.5rem;
        transition: all 0.3s;
    }

    .btn-gold-save:hover {
        background-color: #96722c;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(182, 140, 54, 0.2);
    }

    .stats-card {
        border: none;
        border-radius: 10px;
    }

    .delete-section {
        background-color: var(--danger-soft);
        border: 1px solid #ffe3e3;
        border-radius: 10px;
        padding: 1.5rem;
    }
</style>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-md-start">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('menages.show', $menage) }}" class="text-muted">Ménage</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('menages.classeurs.index', $menage) }}" class="text-muted">Classeurs</a></li>
                    <li class="breadcrumb-item active text-primary-gold fw-bold">{{ $classeur->theme }}</li>
                </ol>
            </nav>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h2 class="h3 fw-bold text-dark mb-0">
                    <i class="bi bi-pencil-square me-2 text-primary-gold"></i>Modifier le Classeur
                </h2>
                <a href="{{ route('menages.classeurs.show', [$menage, $classeur]) }}" class="btn btn-light border btn-sm px-3">
                    <i class="bi bi-x-lg me-1"></i> Annuler les changements
                </a>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-octagon-fill me-2"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <form action="{{ route('menages.classeurs.update', [$menage, $classeur]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="card edit-card shadow-sm mb-4">
                    <div class="edit-header">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-info-circle me-2 text-primary-gold"></i>Détails du classeur</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label-custom mb-2">Thème du classeur (Verrouillé)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="text" class="form-control locked-field py-2" value="{{ $classeur->theme }}" readonly>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i> Le thème est fixe pour garantir la cohérence de l'archivage numérique.
                            </small>
                        </div>

                        <div class="mb-0">
                            <label class="form-label-custom mb-2" for="description">Description personnalisée</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="6"
                                      placeholder="Mettez à jour la description...">{{ old('description', $classeur->description) }}</textarea>
                            @error('description') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0 p-4">
                        <button type="submit" class="btn btn-gold-save shadow-sm">
                            <i class="bi bi-check2-circle me-2"></i>Mettre à jour le classeur
                        </button>
                    </div>
                </div>
            </form>

            <div class="delete-section shadow-sm">
                <div class="d-flex align-items-start">
                    <div class="bg-white p-3 rounded-3 me-3 shadow-sm">
                        <i class="bi bi-trash3 text-danger fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-danger">Zone de danger</h6>
                        <p class="text-muted small mb-3">
                            La suppression d'un classeur est une action irréversible. Cela entraînera également la suppression de tous les documents numérisés qu'il contient.
                        </p>
                        <button type="button" class="btn btn-outline-danger btn-sm fw-bold px-4" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            Supprimer définitivement ce classeur
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card stats-card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="form-label-custom mb-4"><i class="bi bi-bar-chart-fill me-2"></i>État du contenu</h6>
                    <div class="text-center py-4">
                        <div class="display-3 fw-bold text-dark mb-1">{{ $classeur->documents->count() }}</div>
                        <div class="text-muted text-uppercase fw-bold small">Documents archivés</div>
                    </div>
                    <hr class="text-muted opacity-25">
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Date de création :</span>
                            <span class="fw-bold text-dark">{{ $classeur->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Dernière modif :</span>
                            <span class="fw-bold text-dark">{{ $classeur->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, var(--secondary-blue) 0%, #252f6b 100%); color: white;">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-lightbulb me-2 text-primary-gold"></i>Astuce</h6>
                    <p class="small mb-0 opacity-75">
                        Utilisez la description pour préciser si ce classeur contient des documents originaux, des copies certifiées ou des documents en attente de renouvellement.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <i class="bi bi-exclamation-triangle display-1 text-danger"></i>
                </div>
                <h4 class="fw-bold text-dark">Confirmer la suppression</h4>
                <p class="text-muted px-3">
                    Êtes-vous sûr de vouloir supprimer le classeur <strong>{{ $classeur->theme }}</strong> ? <br>
                    Toutes les pièces jointes associées seront <strong>définitivement effacées</strong> des serveurs.
                </p>
                <div class="d-flex gap-2 justify-content-center mt-4">
                    <button type="button" class="btn btn-light px-4 border" data-bs-dismiss="modal">Annuler</button>
                    <form action="{{ route('menages.classeurs.destroy', [$menage, $classeur]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">Confirmer la suppression</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection