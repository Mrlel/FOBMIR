@extends('layouts.admin')

@section('content')
<style>
    :root {
        --primary-green: #198754;
        --secondary-blue: #1e3a5f;
        --light-bg: #f8fafc;
    }

    body { background-color: var(--light-bg) !important; }

    /* Carte épurée */
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        background: #fff;

    }

    .card-header-custom {
        padding: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        background: transparent;
    }

    /* Labels et Inputs */
    .form-label {
        font-weight: 600;
        color: var(--secondary-blue);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1);
    }

    /* Radio buttons personnalisés */
    .gender-option {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        display: inline-block;
        cursor: pointer;
        transition: all 0.2s;
        margin-right: 10px;
    }

    .form-check-input:checked + .form-check-label-custom {
        color: var(--primary-green);
        font-weight: bold;
    }

    .btn-save {
        background-color: var(--primary-green);
        border: none;
        padding: 0.8rem;
        font-weight: 600;
        border-radius: 8px;
        color: #fff;
    }

    .btn-cancel {
        background-color: transparent;
        color: #64748b;
        border: none;
        font-weight: 500;
    }
</style>


    <div class="card card-custom">
        <div class="card-header-custom">
            <h1 class="h4 fw-bold mb-0 text-dark">
                <i class="fas fa-plus-circle text-success me-2"></i> Nouveau Ménage
            </h1>
            <p class="text-muted small mb-0 mt-1">Remplissez les informations pour enregistrer un nouveau foyer.</p>
        </div>

        <div class="card-body p-4">
            <form method="POST" action="{{ route('menages.store') }}">
                @csrf

                <div class="row g-4">
                    <div class="col-md-8">
                        <label for="nom_chef" class="form-label">Nom du chef de ménage <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nom_chef') is-invalid @enderror" 
                               id="nom_chef" name="nom_chef" placeholder="Nom complet" value="{{ old('nom_chef') }}" required>
                        @error('nom_chef')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="nb_individus" class="form-label">Taille du ménage</label>
                        <input type="number" class="form-control @error('nb_individus') is-invalid @enderror" 
                               id="nb_individus" name="nb_individus" placeholder="Ex: 4" value="{{ old('nb_individus') }}" min="1">
                        @error('nb_individus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label d-block">Sexe du chef de ménage <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sexe_chef" id="sexe_m" value="M" 
                                       {{ old('sexe_chef') == 'M' ? 'checked' : '' }} required>
                                <label class="form-check-label ms-1" for="sexe_m">Masculin</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sexe_chef" id="sexe_f" value="F" 
                                       {{ old('sexe_chef') == 'F' ? 'checked' : '' }}>
                                <label class="form-check-label ms-1" for="sexe_f">Féminin</label>
                            </div>
                        </div>
                        @error('sexe_chef')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="origine_id" class="form-label">Origine du ménage</label>
                        <select class="form-select @error('origine_id') is-invalid @enderror" 
                                id="origine_id" name="origine_id">
                            <option value="">-- Sélectionner l'origine --</option>
                            @foreach($origines as $origine)
                                <option value="{{ $origine->id }}" {{ old('origine_id') == $origine->id ? 'selected' : '' }}>
                                    {{ $origine->libelle }}
                                </option>
                            @endforeach
                        </select>
                        @error('origine_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="sous_quartier_id" class="form-label">Sous-quartier <span class="text-danger">*</span></label>
                        <select class="form-select @error('sous_quartier_id') is-invalid @enderror" 
                                id="sous_quartier_id" name="sous_quartier_id" required>
                            <option value="">-- Sélectionner le quartier --</option>
                            @foreach($sousQuartiers as $sousQuartier)
                                <option value="{{ $sousQuartier->id }}" {{ old('sous_quartier_id') == $sousQuartier->id ? 'selected' : '' }}>
                                    {{ $sousQuartier->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('sous_quartier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mt-4">
                        <div class="d-flex flex-column gap-2">
                            <button type="submit" class="btn btn-save shadow-sm">
                                <i class="fas fa-check me-2"></i> Enregistrer le ménage
                            </button>
                            <a href="{{ route('menages.index') }}" class="btn btn-cancel">
                                Annuler et retourner à la liste
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection