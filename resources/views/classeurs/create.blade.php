@extends('layouts.admin')

@section('title', 'Nouveau classeur - ' . $menage->nom_chef)

@section('content')

<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --soft-bg: #f4f7fa;
    }

    /* Form Design */
    .form-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .form-header {
        background-color: var(--secondary-blue);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 1.25rem;
    }

    .form-label {
        font-weight: 700;
        color: var(--secondary-blue);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control:focus, .custom-select:focus {
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 0.2rem rgba(182, 140, 54, 0.15);
    }

    /* Sidebar Context */
    .context-card {
        border: none;
        background: white;
    }

    .theme-preview-box {
        background-color: #fcf9f2;
        border-left: 3px solid var(--primary-gold);
        transition: all 0.3s ease;
    }

    .btn-gold {
        background-color: var(--primary-gold);
        color: white;
        border: none;
        padding: 0.8rem 2rem;
        font-weight: 700;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .btn-gold:hover {
        background-color: #96722c;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(182, 140, 54, 0.3);
    }

    .list-existing-item {
        border: none;
        padding: 0.75rem 1rem;
        background: #f8fafc;
        margin-bottom: 5px;
        border-radius: 8px;
        font-size: 0.85rem;
    }
</style>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('menages.index') }}" class="text-muted">Ménages</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('menages.show', $menage) }}" class="text-muted">{{ $menage->nom_chef }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('menages.classeurs.index', $menage) }}" class="text-muted">Classeurs</a></li>
                    <li class="breadcrumb-item active text-primary-gold fw-bold">Nouveau</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h3 fw-bold text-dark mb-0">
                    <i class="bi bi-folder-plus me-2 text-primary-gold"></i>Nouveau Classeur
                </h2>
                <a href="{{ route('menages.classeurs.index', $menage) }}" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('menages.classeurs.store', $menage) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card form-card shadow-sm">
                    <div class="form-header">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-gear-fill me-2"></i>Configuration du classeur thématique</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label" for="theme">Thème du classeur <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="bi bi-tag-fill"></i>
                                </span>
                                <select class="form-select border-start-0 py-2 @error('theme') is-invalid @enderror" 
                                        id="theme" name="theme" required>
                                    <option value="" selected disabled>Choisir une thématique...</option>
                                    @foreach($themesDisponibles as $theme => $description)
                                        <option value="{{ $theme }}" 
                                                {{ old('theme') == $theme ? 'selected' : '' }}
                                                data-description="{{ $description }}">
                                            {{ $theme }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('theme') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                            
                            <div class="mt-3 p-3 theme-preview-box rounded shadow-sm" style="display: none;" id="desc-box">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-primary-gold me-2"></i>
                                    <small class="text-dark fst-italic" id="theme-description"></small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label" for="description">Observations / Précisions</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4"
                                      placeholder="Ex: Regroupe l'ensemble des carnets de santé et certificats médicaux de la famille...">{{ old('description') }}</textarea>
                            @error('description') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                            <div class="form-text mt-2 small">
                                <i class="bi bi-lightbulb me-1 text-warning"></i>
                                Si laissé vide, la description standard du thème sera appliquée par défaut.
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0 p-4 d-flex align-items-center">
                        <button type="submit" class="btn btn-gold shadow-sm">
                            <i class="bi bi-check-circle me-2"></i>Finaliser la création
                        </button>
                        <a href="{{ route('menages.classeurs.index', $menage) }}" class="btn btn-link text-muted ms-3 text-decoration-none">Annuler</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card context-card shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-secondary-blue mb-3">CONCOURS AU MÉNAGE</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light p-2 rounded-3 me-3">
                                <i class="bi bi-house-door text-primary-gold fs-4"></i>
                            </div>
                            <div class="overflow-hidden">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Chef de ménage</small>
                                <span class="fw-bold text-dark text-truncate d-block">{{ $menage->nom_chef }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-light p-2 rounded-3 me-3">
                                <i class="bi bi-archive text-primary-gold fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Pochette active</small>
                                <span class="fw-bold text-dark">{{ $pochette->libelle }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($pochette->classeurs->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-3">
                        <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">CLASSEURS DÉJÀ EXISTANTS ({{ $pochette->classeurs->count() }})</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($pochette->classeurs as $classeurExistant)
                                <div class="list-existing-item d-flex align-items-center">
                                    <i class="bi bi-folder-check text-success me-2"></i>
                                    <span class="text-dark">{{ $classeurExistant->theme }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 p-2 bg-yellow-light rounded" style="background-color: #fff9e6; border: 1px dashed #ffeeba;">
                            <small class="text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-info-circle me-1"></i> Évitez les doublons pour garder une structure claire.
                            </small>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Utilisation des Bootstrap Icons via classes bi
    $('#theme').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const description = selectedOption.data('description');
        const descriptionElement = $('#theme-description');
        const descBox = $('#desc-box');
        
        if (description) {
            descriptionElement.text(description);
            descBox.slideDown(200);
        } else {
            descBox.slideUp(200);
        }
    });
    
    // Déclenchement initial si redirection avec old data
    if($('#theme').val()) {
        $('#theme').trigger('change');
    }
});
</script>
@endpush