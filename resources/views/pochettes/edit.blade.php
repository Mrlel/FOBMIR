@extends('layouts.admin')

@section('title', 'Modifier la pochette - ' . $menage->nom_chef)

@section('content')

            <div class="card border-0 shadow-sm" style="border-radius: 8px; overflow: hidden;">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background-color: #171e4c;">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="bi bi-pencil-square me-2" style="color: #b68c36;"></i>Modifier la Pochette
                    </h5>
                    <a href="{{ route('menages.pochette.show', $menage) }}" class="btn btn-sm btn-light" style="border-radius: 8px;">
                        <i class="bi bi-x-lg me-1"></i> Annuler
                    </a>
                </div>

                <form action="{{ route('menages.pochette.update', $menage) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body p-4 bg-white">
                        <div class="p-3 mb-4 border-0 shadow-sm" style="background-color: #fcf8f0; border-radius: 4px; border: 2px solid #b68c36 !important;">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="mb-1 fw-bold" style="color: #171e4c;">Ménage : {{ $menage->nom_chef }}</h6>
                                    <p class="mb-0 text-muted small">
                                        <i class="bi bi-geo-alt me-1"></i> 
                                        {{ $menage->sousQuartier->nom ?? 'Localisation non définie' }} 
                                        <span class="mx-2">•</span>
                                        <i class="bi bi-people me-1"></i>
                                        {{ $menage->nb_individus ?? 0 }} membre(s)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mt-2">
                            <div class="col-md-7">
                                <h5 class="mb-4 fw-bold small text-uppercase" style="color: #171e4c; letter-spacing: 1px;">
                                    <i class="bi bi-gear-fill me-2" style="color: #b68c36;"></i>Paramètres généraux
                                </h5>
                                
                                <div class="mb-4">
                                    <label for="libelle" class="form-label fw-bold small text-muted">Nom de la pochette <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control form-control-lg @error('libelle') is-invalid @enderror" 
                                           style="border-radius: 8px; border: 1px solid #e0e0e0; font-size: 1rem;"
                                           id="libelle" 
                                           name="libelle" 
                                           value="{{ old('libelle', $pochette->libelle) }}" 
                                           placeholder="Ex: Dossier Familial - {{ $menage->nom_chef }}"
                                           required>
                                    @error('libelle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="form-label fw-bold small text-muted">Description / Notes</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              style="border-radius: 8px; border: 1px solid #e0e0e0;"
                                              id="description" 
                                              name="description" 
                                              rows="5"
                                              placeholder="Informations complémentaires...">{{ old('description', $pochette->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-5">
                                <h5 class="mb-4 fw-bold small text-uppercase" style="color: #171e4c; letter-spacing: 1px;">
                                    <i class="bi bi-graph-up me-2" style="color: #b68c36;"></i>Résumé actuel
                                </h5>
                                
                                <div class="card border-0" style="background-color: #f8f9fa; border-radius: 10px;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="text-muted small">Date de création</span>
                                            <span class="fw-bold small" style="color: #171e4c;">{{ $pochette->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="text-muted small">Classeurs actifs</span>
                                            <span class="badge px-3 py-2" style="background-color: #171e4c; border-radius: 30px;">{{ $pochette->classeurs->count() }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-0">
                                            <span class="text-muted small">Total documents</span>
                                            <span class="badge px-3 py-2" style="background-color: #b68c36; border-radius: 30px;">{{ $pochette->classeurs->sum(fn($c) => $c->documents->count()) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 p-3 d-flex border-0" style="background-color: rgba(182, 140, 54, 0.05); border-radius: 10px;">
                                    <i class="bi bi-exclamation-triangle-fill text-warning me-3 fs-4"></i>
                                    <p class="mb-0 small text-muted">
                                        <strong>Note :</strong> Toute modification du nom de la pochette sera immédiatement répercutée dans l'arborescence des fichiers.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light p-4 border-0 text-end">
                        <button type="submit" class="btn text-white fw-bold px-5 py-2 shadow-sm" style="background-color: #b68c36; border-radius: 8px; border: none;">
                            <i class="bi bi-check-circle me-2"></i>Mettre à jour la pochette
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #b68c36;
        box-shadow: 0 0 0 0.25rem rgba(182, 140, 54, 0.1);
    }
</style>
@endsection