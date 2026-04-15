@extends('layouts.admin')

@section('title', 'Modifier le dossier - ' . $dossier->nom)

@section('content')

            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background-color: #171e4c;">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="bi bi-pencil-square me-2" style="color: #b68c36;"></i>Modifier le dossier individuel
                    </h5>
                    <a href="{{ route('menages.dossiers.show', [$menage, $dossier]) }}" class="btn btn-sm btn-light" style="border-radius: 8px;">
                        <i class="bi bi-arrow-left me-1"></i> Retour
                    </a>
                </div>

                <form action="{{ route('menages.dossiers.update', [$menage, $dossier]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body p-4 bg-white">
                        @if(session('error'))
                            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 10px;">
                                <i class="bi bi-exclamation-octagon-fill me-2"></i> {{ session('error') }}
                            </div>
                        @endif

                        <div class="p-3 mb-4 border-0 shadow-sm" style="background-color: #f8f9fa; border-radius: 4px; border: 2px solid #171e4c !important;">
                            <div class="row text-dark">
                                <div class="col-md-6 border-end-md">
                                    <div class="small text-muted fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Contexte Propriétaire</div>
                                    <div class="fw-bold" style="color: #171e4c;">
                                        <i class="bi bi-person-circle me-1"></i> {{ $dossier->individuMenage->prenom }} {{ $dossier->individuMenage->nom }}
                                    </div>
                                    <div class="small mt-1">Ménage : {{ $menage->nom_chef }}</div>
                                </div>
                                <div class="col-md-6 ps-md-4">
                                    <div class="small text-muted fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Informations Archivage</div>
                                    <div>Pochette : <span class="badge bg-white text-dark border shadow-sm">{{ $dossier->pochette->libelle }}</span></div>
                                    <div class="small mt-1 text-muted">Initialisé le : {{ $dossier->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-8">
                                <h5 class="mb-4 fw-bold small text-uppercase" style="color: #171e4c; letter-spacing: 1px;">
                                    <i class="bi bi-info-circle-fill me-2" style="color: #b68c36;"></i>Édition des détails
                                </h5>

                                <div class="mb-4">
                                    <label for="nom" class="form-label fw-bold small text-muted">Nom du dossier <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control form-control-lg @error('nom') is-invalid @enderror" 
                                           style="border-radius: 8px; border: 1px solid #e0e0e0;"
                                           id="nom" 
                                           name="nom" 
                                           value="{{ old('nom', $dossier->nom) }}" 
                                           required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="form-label fw-bold small text-muted">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              style="border-radius: 8px; border: 1px solid #e0e0e0;"
                                              id="description" 
                                              name="description" 
                                              rows="5"
                                              placeholder="Notes sur le contenu du dossier...">{{ old('description', $dossier->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; background-color: #fcf8f0;">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3" style="color: #171e4c;">
                                            <i class="bi bi-bar-chart-fill me-2" style="color: #b68c36;"></i>État actuel
                                        </h6>
                                        <div class="row text-center g-2">
                                            <div class="col-6">
                                                <div class="bg-white p-2 rounded shadow-sm border">
                                                    <h4 class="mb-0 fw-bold" style="color: #171e4c;">{{ $dossier->classeurs->count() }}</h4>
                                                    <small class="text-muted" style="font-size: 0.7rem;">Classeurs</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="bg-white p-2 rounded shadow-sm border">
                                                    <h4 class="mb-0 fw-bold" style="color: #b68c36;">{{ $dossier->classeurs->sum(fn($c) => $c->documents->count()) }}</h4>
                                                    <small class="text-muted" style="font-size: 0.7rem;">Documents</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #171e4c;">
                                    <div class="card-body text-white">
                                        <h6 class="fw-bold mb-2 small opacity-75 text-uppercase">
                                            <i class="bi bi-clock-history me-2"></i>Historique
                                        </h6>
                                        <p class="mb-0 small">Dernière modification :</p>
                                        <div class="fw-bold" style="color: #b68c36;">{{ $dossier->updated_at->format('d/m/Y à H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light p-4 border-0 text-end">
                        <button type="submit" class="btn text-white fw-bold px-5 py-2 shadow-sm" style="background-color: #171e4c; border-radius: 8px; border: none;">
                            <i class="bi bi-save me-2" style="color: #b68c36;"></i>Enregistrer les modifications
                        </button>
                        <a href="{{ route('menages.dossiers.show', [$menage, $dossier]) }}" class="btn btn-outline-secondary px-4 py-2 ms-2" style="border-radius: 8px;">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>

<style>
    .form-control:focus {
        border-color: #b68c36;
        box-shadow: 0 0 0 0.25rem rgba(182, 140, 54, 0.1);
    }
    @media (min-width: 768px) {
        .border-end-md { border-right: 1px solid #dee2e6; }
    }
</style>
@endsection