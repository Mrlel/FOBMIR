@extends('layouts.individu')

@section('content')

<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --danger-light: #fff5f5;
    }

    .form-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .form-header-edit {
        background-color: white;
        padding: 1.5rem;
        border-bottom: 2px solid #f8f9fa;
    }

    .form-label-custom {
        font-weight: 600;
        color: var(--secondary-blue);
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Champ verrouillé pour le citoyen */
    .locked-field {
        background-color: #f8f9fa !important;
        border: 1px dashed #ced4da !important;
        color: #6c757d;
        cursor: not-allowed;
        font-weight: 600;
    }

    .custom-textarea {
        border: 2px solid #f0f0f0;
        border-radius: 12px;
        padding: 0.75rem;
        transition: 0.3s;
    }

    .custom-textarea:focus {
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 0.25rem rgba(182, 140, 54, 0.1);
        outline: none;
    }

    .btn-update {
        background-color: var(--secondary-blue);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.8rem 2rem;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-update:hover {
        background-color: var(--primary-gold);
        color: white;
        transform: translateY(-2px);
    }

    .delete-zone {
        background-color: var(--danger-light);
        border-radius: 15px;
        border: 1px solid #ffe3e3;
    }
</style>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-pencil-square text-primary-gold me-2"></i>Modifier le classeur
            </h3>
            <p class="text-muted mb-0 small">Dossier: <strong>{{ $dossier->nom }}</strong></p>
        </div>
        <a href="{{ route('mes.classeurs') }}" class="btn btn-light border px-4 btn-sm fw-bold">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card form-card shadow-sm">
                <div class="form-header-edit d-flex align-items-center">
                    <div class="bg-light p-2 rounded-3 me-3">
                        <i class="bi bi-folder2 text-primary-gold fs-5"></i>
                    </div>
                    <h6 class="mb-0 fw-bold text-dark">Propriétés du classeur</h6>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('individu.classeurs.update', $classeur) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label-custom">Thème (Non modifiable)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-dashed"><i class="bi bi-lock-fill text-muted"></i></span>
                                <input type="text" class="form-control locked-field" value="{{ $classeur->theme }}" readonly>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                Le thème est verrouillé pour préserver la structure de votre archivage civil.
                            </small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom" for="description">Description</label>
                            <textarea name="description" id="description" rows="5" 
                                      class="form-control custom-textarea" 
                                      placeholder="Notez ici des détails sur le contenu...">{{ old('description', $classeur->description) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <button type="submit" class="btn btn-update px-5 shadow-sm">
                                <i class="bi bi-check2-circle me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-bar-chart-fill text-primary-gold me-2"></i>Statistiques</h6>
                    <div class="text-center py-3 bg-light rounded-4 mb-3">
                        <h2 class="fw-bold text-dark mb-0">{{ $classeur->documents->count() }}</h2>
                        <span class="text-muted small text-uppercase fw-bold">Documents</span>
                    </div>
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Créé le :</span>
                            <span class="fw-bold text-dark">{{ $classeur->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="delete-zone p-4 text-center">
                <h6 class="fw-bold text-danger mb-2">Zone critique</h6>
                <p class="small text-muted mb-3">Toute suppression supprimera définitivement les fichiers joints.</p>
                <form action="" method="POST" onsubmit="return confirm('Attention : Tous les documents de ce classeur seront supprimés. Confirmer ?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-link text-danger text-decoration-none fw-bold small p-0">
                        <i class="bi bi-trash3 me-1"></i> Supprimer ce classeur
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection