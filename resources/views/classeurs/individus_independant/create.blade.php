@extends('layouts.individu')

@section('content')

<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
    }

    .form-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .form-header {
        background-color: var(--secondary-blue);
        color: white;
        padding: 1.5rem;
        border-bottom: 4px solid var(--primary-gold);
    }

    .form-label-custom {
        font-weight: 600;
        color: var(--secondary-blue);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .custom-select, .custom-textarea {
        border: 2px solid #f0f0f0;
        border-radius: 12px;
        padding: 0.75rem;
        transition: all 0.3s;
    }

    .custom-select:focus, .custom-textarea:focus {
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 0.25rem rgba(182, 140, 54, 0.1);
        outline: none;
    }

    .theme-badge {
        background: rgba(182, 140, 54, 0.08);
        color: var(--primary-gold);
        border: 1px solid rgba(182, 140, 54, 0.2);
        border-radius: 8px;
        padding: 8px 15px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: 0.3s;
    }

    .btn-save {
        background-color: var(--primary-gold);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.8rem 2rem;
        font-weight: 700;
        transition: 0.3s;
    }

    .btn-save:hover {
        background-color: #96722c;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(182, 140, 54, 0.3);
        color: white;
    }

    /* Style Verre pour les infos */
    .info-glass {
        background: rgba(248, 249, 250, 0.8);
        backdrop-filter: blur(5px);
        border-radius: 15px;
        padding: 1.5rem;
        border: 1px solid #eee;
    }
</style>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-folder-plus text-primary-gold me-2"></i>Nouveau classeur
            </h3>
            <p class="text-muted mb-0 small">
                <i class="bi bi-info-circle me-1"></i> Dossier cible : <strong>{{ $dossier->nom }}</strong>
            </p>
        </div>
        <a href="{{ route('mes.classeurs') }}" class="btn btn-light border px-4 btn-sm fw-bold">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card form-card">
                <div class="form-header">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Informations du classeur</h6>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('individu.classeurs.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label-custom">Thème du classeur <span class="text-danger">*</span></label>
                            <select name="theme" class="form-select custom-select" required>
                                <option value="" selected disabled>Choisir un thème...</option>
                                @foreach($themesDisponibles as $theme => $desc)
                                    <option value="{{ $theme }}" {{ old('theme') == $theme ? 'selected' : '' }}>
                                        {{ $theme }}
                                    </option>
                                @endforeach
                            </select>
                            @error('theme')
                                <div class="text-danger small mt-2 fw-bold"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom">Description (optionnel)</label>
                            <textarea name="description" rows="4" class="form-control custom-textarea" 
                                      placeholder="Ex: Documents originaux et copies pour l'année en cours...">{{ old('description') }}</textarea>
                            <small class="text-muted">Précisez le contenu pour vous y retrouver plus facilement.</small>
                        </div>

                        <hr class="my-4 opacity-25">

                        <button type="submit" class="btn btn-save w-100 w-md-auto">
                            <i class="bi bi-check-circle me-2"></i>Créer le classeur
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="info-glass shadow-sm h-100">
                <h6 class="fw-bold text-secondary mb-3">
                    <i class="bi bi-collection text-primary-gold me-2"></i>Thèmes disponibles
                </h6>
                <p class="small text-muted mb-4">
                    Les thèmes permettent de classer automatiquement vos documents par catégorie légale.
                </p>
                
                <div class="d-flex flex-column gap-3">
                    @foreach($themesDisponibles as $theme => $desc)
                        <div class="d-flex align-items-start p-2 rounded-3 hover-bg-light transition">
                            <span class="theme-badge me-3 mt-1">{{ $loop->iteration }}</span>
                            <div>
                                <div class="fw-bold text-dark small">{{ $theme }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $desc }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-3 border-top">
                    <div class="alert bg-white border-0 shadow-sm small text-muted mb-0">
                        <i class="bi bi-shield-lock-fill text-primary-gold me-2"></i>
                        Une fois créé, le thème du classeur ne pourra plus être modifié pour garantir la validité de l'archivage.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection