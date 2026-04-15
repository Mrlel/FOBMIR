@extends('layouts.admin')

@section('title', 'Modifier le ménage - ' . $menage->nom_chef)

@section('content')
<style>
    :root {
        --primary-green: #198754;
        --secondary-blue: #1e3a5f;
        --light-bg: #f8fafc;
    }

    /* Carte principale sans bordures latérales */
    .card {
        border: none !important;
        border-radius: 12px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background-color: transparent !important;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem;
    }

    /* Styles des formulaires */
    .form-label {
        font-weight: 600;
        color: var(--secondary-blue);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.65rem 1rem;
    }

    .form-control:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1);
    }

    /* Cartes latérales informatives (Remplacement des border-left) */
    .info-box {
        background-color: #f1f5f9; /* Gris très léger */
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border: none;
    }

    .info-box-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--secondary-blue);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .info-box-title i { color: var(--primary-green); }

    .btn-save {
        background-color: var(--primary-green);
        border: none;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold text-dark mb-0">
                <i class="fas fa-edit text-success me-2"></i> Modifier le ménage
            </h1>
            <p class="text-muted small mb-0">{{ $menage->nom_chef }}</p>
        </div>
        <a href="{{ route('menages.show', $menage) }}" class="btn btn-outline-secondary shadow-sm btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>

    <form action="{{ route('menages.update', $menage) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card p-2">
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label for="nom_chef" class="form-label">Nom du chef de ménage <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nom_chef') is-invalid @enderror" 
                                       id="nom_chef" name="nom_chef" value="{{ old('nom_chef', $menage->nom_chef) }}" required>
                                @error('nom_chef')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nb_individus" class="form-label">Nombre d'individus</label>
                                <input type="number" class="form-control @error('nb_individus') is-invalid @enderror" 
                                       id="nb_individus" name="nb_individus" value="{{ old('nb_individus', $menage->nb_individus) }}" min="1">
                                <small class="text-muted mt-1 d-block small">Enregistrés : {{ $menage->individus->count() }}</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label d-block">Sexe du chef de ménage</label>
                                <div class="mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sexe_chef" id="sexe_m" value="M" 
                                               {{ old('sexe_chef', $menage->sexe_chef) == 'M' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sexe_m">Masculin</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sexe_chef" id="sexe_f" value="F" 
                                               {{ old('sexe_chef', $menage->sexe_chef) == 'F' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sexe_f">Féminin</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="origine_id" class="form-label">Origine du ménage</label>
                                <select class="form-select @error('origine_id') is-invalid @enderror" id="origine_id" name="origine_id">
                                    <option value="">Sélectionner une origine</option>
                                    @foreach($origines as $origine)
                                        <option value="{{ $origine->id }}" {{ old('origine_id', $menage->origine_id) == $origine->id ? 'selected' : '' }}>
                                            {{ $origine->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="sous_quartier_id" class="form-label">Sous-quartier <span class="text-danger">*</span></label>
                                <select class="form-select @error('sous_quartier_id') is-invalid @enderror" id="sous_quartier_id" name="sous_quartier_id" required>
                                    @foreach($sousQuartiers as $sousQuartier)
                                        <option value="{{ $sousQuartier->id }}" {{ old('sous_quartier_id', $menage->sous_quartier_id) == $sousQuartier->id ? 'selected' : '' }}>
                                            {{ $sousQuartier->nom }} - ({{ $sousQuartier->quartier->nom ?? 'Quartier non défini' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 d-flex gap-2 pb-4">
                        <button type="submit" class="btn btn-save text-white shadow-sm">
                            <i class="fas fa-save me-1"></i> Enregistrer
                        </button>
                        <a href="{{ route('menages.show', $menage) }}" class="btn btn-light border">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="info-box">
                    <h6 class="info-box-title">
                        <i class="fas fa-map-marker-alt me-2"></i> Localisation actuelle
                    </h6>
                    @if($menage->sousQuartier)
                        <div class="small">
                            <div class="mb-1"><strong>Quartier :</strong> {{ $menage->sousQuartier->quartier->nom ?? 'N/A' }}</div>
                            <div class="mb-1"><strong>Village :</strong> {{ $menage->sousQuartier->quartier->village->nom ?? 'N/A' }}</div>
                        </div>
                    @else
                        <p class="text-muted small mb-0">Aucune localisation définie</p>
                    @endif
                </div>

                <div class="info-box">
                    <h6 class="info-box-title">
                        <i class="fas fa-bolt me-2"></i> Actions rapides
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('menages.pochette.show', $menage) }}" class="btn btn-white btn-sm border text-start">
                            <i class="fas fa-folder text-info me-2"></i> Accéder à la pochette
                        </a>
                        <button type="button" class="btn btn-white btn-sm border text-start text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-2"></i> Supprimer le ménage
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title h6">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Voulez-vous vraiment supprimer le ménage de <strong>{{ $menage->nom_chef }}</strong> ?</p>
                <div class="p-3 bg-light rounded small">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Cette action supprimera également les individus et documents associés.
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('menages.destroy', $menage) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection