@extends('layouts.admin')

@section('title', 'Nouveau dossier individuel - ' . $menage->nom_chef)

@section('content')

            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background-color: #171e4c;">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="bi bi-folder-plus me-2" style="color: #b68c36;"></i>Créer un dossier individuel
                    </h5>
                    <a href="{{ route('menages.dossiers.index', $menage) }}" class="btn btn-sm btn-light" style="border-radius: 8px;">
                        <i class="bi bi-arrow-left me-1"></i> Retour
                    </a>
                </div>

                <form action="{{ route('menages.dossiers.store', $menage) }}" method="POST">
                    @csrf
                    <div class="card-body p-4 bg-white">
                        
                        @if(session('error'))
                            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 10px;">
                                <i class="bi bi-exclamation-octagon-fill me-2"></i> {{ session('error') }}
                            </div>
                        @endif

                        <div class="p-3 mb-4 border-0 shadow-sm" style="background-color: #fcf8f0; border-radius: 4px; border: 2px solid #b68c36 !important;">
                            <div class="row align-items-center">
                                <div class="col-md-4 border-end-md">
                                    <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.65rem;">Chef de ménage</small>
                                    <span class="fw-bold" style="color: #171e4c;">{{ $menage->nom_chef }}</span>
                                </div>
                                <div class="col-md-4 border-end-md">
                                    <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.65rem;">Pochette parente</small>
                                    <span class="text-dark">{{ $pochette->libelle }}</span>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.65rem;">Dossiers existants</small>
                                    <span class="badge" style="background-color: #171e4c;">{{ $pochette->dossiers->count() }} dossiers</span>
                                </div>
                            </div>
                        </div>

                        @if($individusSansDossier->count() > 0)
                            <div class="row g-4">
                                <div class="col-md-7">
                                    <h5 class="mb-4 fw-bold small text-uppercase" style="color: #171e4c; letter-spacing: 1px;">
                                        <i class="bi bi-person-lines-fill me-2" style="color: #b68c36;"></i>Informations du dossier
                                    </h5>

                                    <div class="mb-4">
                                        <label for="individu_menage_id" class="form-label fw-bold small text-muted">Individu concerné <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-lg @error('individu_menage_id') is-invalid @enderror" 
                                                style="border-radius: 8px; border: 1px solid #e0e0e0;"
                                                id="individu_menage_id" 
                                                name="individu_menage_id" 
                                                required>
                                            <option value="">Sélectionner un membre du ménage</option>
                                            @foreach($individusSansDossier as $individu)
                                                <option value="{{ $individu->id }}" 
                                                        {{ old('individu_menage_id', request('individu')) == $individu->id ? 'selected' : '' }}
                                                        data-nom="{{ $individu->prenom }} {{ $individu->nom }}">
                                                    {{ $individu->prenom }} {{ $individu->nom }} @if($individu->telephone) ({{ $individu->telephone }}) @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('individu_menage_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="nom" class="form-label fw-bold small text-muted">Nom personnalisé du dossier</label>
                                        <input type="text" 
                                               class="form-control form-control-lg @error('nom') is-invalid @enderror" 
                                               style="border-radius: 8px; border: 1px solid #e0e0e0;"
                                               id="nom" 
                                               name="nom" 
                                               value="{{ old('nom') }}" 
                                               placeholder="Ex: Dossier Médical - {{ $menage->nom_chef }}">
                                        <div class="form-text small mt-2">
                                            <i class="bi bi-info-circle me-1"></i> Si vide, le nom sera : <strong>Dossier de [Prénom] [Nom]</strong>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="description" class="form-label fw-bold small text-muted">Description / Notes</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  style="border-radius: 8px; border: 1px solid #e0e0e0;"
                                                  id="description" 
                                                  name="description" 
                                                  rows="4"
                                                  placeholder="Observations particulières sur ce dossier...">{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="card border-0 mb-3" style="background-color: #f8f9fa; border-radius: 12px;">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-3" style="color: #171e4c;">
                                                <i class="bi bi-lightbulb-fill me-2" style="color: #b68c36;"></i>Rappel du système
                                            </h6>
                                            <ul class="list-unstyled small text-muted mb-0">
                                                <li class="mb-2 d-flex"><i class="bi bi-check2-circle text-success me-2"></i> 1 dossier par individu max.</li>
                                                <li class="mb-2 d-flex"><i class="bi bi-check2-circle text-success me-2"></i> Sert de base aux classeurs thématiques.</li>
                                                <li class="d-flex"><i class="bi bi-check2-circle text-success me-2"></i> Accès sécurisé par rôle.</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="card border-0 shadow-sm" id="apercu" style="display: none; background: linear-gradient(135deg, #171e4c 0%, #2a356b 100%); border-radius: 12px;">
                                        <div class="card-body text-white">
                                            <h6 class="fw-bold mb-3 small opacity-75 text-uppercase">Aperçu du dossier</h6>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; background: rgba(182, 140, 54, 0.2); color: #b68c36;">
                                                    <i class="bi bi-person-badge fs-4"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-truncate" id="apercu-nom" style="max-width: 200px;">-</div>
                                                    <div class="small opacity-75" id="apercu-individu">-</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3 opacity-25" style="color: #171e4c;">
                                    <i class="bi bi-people-fill" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="fw-bold" style="color: #171e4c;">Tous les individus ont déjà un dossier</h5>
                                <p class="text-muted small">Chaque membre actuel du ménage possède déjà un dossier personnel configuré.</p>
                                <a href="{{ route('menages.dossiers.index', $menage) }}" class="btn text-white px-4" style="background-color: #171e4c; border-radius: 8px;">
                                    <i class="bi bi-list-ul me-2"></i>Voir la liste des dossiers
                                </a>
                            </div>
                        @endif
                    </div>

                    @if($individusSansDossier->count() > 0)
                        <div class="card-footer bg-light p-4 border-0 text-end">
                            <button type="submit" class="btn text-white fw-bold px-5 py-2 shadow-sm" style="background-color: #b68c36; border-radius: 8px; border: none;">
                                <i class="bi bi-check-lg me-2"></i>Créer le dossier
                            </button>
                            <a href="{{ route('menages.dossiers.index', $menage) }}" class="btn btn-outline-secondary px-4 py-2 ms-2" style="border-radius: 8px;">
                                Annuler
                            </a>
                        </div>
                    @endif
                </form>
            </div>

<style>
    .form-control:focus, .form-select:focus {
        border-color: #b68c36;
        box-shadow: 0 0 0 0.25rem rgba(182, 140, 54, 0.1);
    }
    @media (min-width: 768px) {
        .border-end-md { border-right: 1px solid #dee2e6; }
    }
</style>

@push('scripts')
<script>
$(document).ready(function() {
    function updateApercu() {
        const individuSelect = $('#individu_menage_id');
        const nomInput = $('#nom');
        const apercu = $('#apercu');
        const apercuNom = $('#apercu-nom');
        const apercuIndividu = $('#apercu-individu');
        
        const selectedOption = individuSelect.find('option:selected');
        const individuNom = selectedOption.data('nom');
        
        if (individuNom) {
            const nomDossier = nomInput.val() || `Dossier de ${individuNom}`;
            apercuNom.text(nomDossier);
            apercuIndividu.text(individuNom);
            apercu.fadeIn();
        } else {
            apercu.fadeOut();
        }
    }
    
    $('#individu_menage_id, #nom').on('change keyup', updateApercu);
    updateApercu();
});
</script>
@endpush
@endsection