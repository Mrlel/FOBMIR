@extends('layouts.admin')

@section('title', 'Nouveau classeur - ' . $dossier->nom)

@section('content')

<style>
    :root {
        --primary-gold: #b68c36;
        --primary-gold-dark: #96722c;
        --secondary-blue: #171e4c;
        --dark-blue: #0d1231;
    }

    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .card-header-blue {
        background-color: var(--secondary-blue);
        color: #fff;
    }

    .alert-context {
        background-color: #f8fafc;
        border: 2px solid var(--primary-gold);
        border-radius: 4px;
    }

    .side-card {
        border: 1px solid #edf2f7;
        border-radius: 10px;
        background-color: #fdfdfd;
    }

    .btn-gold {
        background-color: var(--primary-gold);
        border: none;
        color: white;
        transition: all 0.3s;
    }

    .btn-gold:hover {
        background-color: var(--primary-gold-dark);
        color: white;
        transform: translateY(-1px);
    }

    .form-label {
        color: var(--secondary-blue);
        font-size: 0.85rem;
        letter-spacing: 0.2px;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 0.25rem rgba(182, 140, 54, 0.1);
    }

    #apercu {
        background: linear-gradient(135deg, #fff 0%, #fcf8f0 100%);
        border: 1px dashed var(--primary-gold);
    }
</style>

            <div class="card card-custom">
                <div class="card-header card-header-blue py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-folder-plus me-2" style="color: var(--primary-gold)"></i>
                        Nouveau classeur thématique
                    </h5>
                    <a href="{{ route('menages.dossiers.show', [$menage, $dossier]) }}" class="btn btn-sm btn-light" style="border-radius: 4px;">
                        <i class="bi bi-arrow-left me-1"></i> Retour
                    </a>
                </div>

                <form action="{{ route('menages.dossiers.classeurs.store', [$menage, $dossier]) }}" method="POST">
                    @csrf
                    <div class="card-body p-4">

                        <div class="alert alert-context shadow-sm mb-4 p-3">
                            <div class="row align-items-center">
                                <div class="col-md-1 d-none d-md-block text-center">
                                    <i class="bi bi-info-circle-fill fs-3" style="color: var(--secondary-blue)"></i>
                                </div>
                                <div class="col-md-11">
                                    <div class="row g-2">
                                        <div class="col-md-6 border-end-md">
                                            <p class="mb-1 small"><strong>Propriétaire :</strong> {{ $dossier->individuMenage->prenom }} {{ $dossier->individuMenage->nom }}</p>
                                            <p class="mb-0 small"><strong>Dossier :</strong> {{ $dossier->nom }}</p>
                                        </div>
                                        <div class="col-md-6 ps-md-3">
                                            <p class="mb-1 small"><strong>Ménage :</strong> {{ $menage->nom_chef }}</p>
                                            <p class="mb-0 small"><strong>Emplacement :</strong> Pochette {{ $dossier->pochette->libelle }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Nom du classeur <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" id="nom" value="{{ old('nom') }}"
                                           class="form-control form-control-lg @error('nom') is-invalid @enderror"
                                           placeholder="Ex: Pièces d'Identité" required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Thématique / Catégorie</label>
                                    <select name="theme" id="theme" class="form-select @error('theme') is-invalid @enderror">
                                        <option value="" selected disabled>Choisir un thème...</option>
                                        <option value="Documents personnels">Documents personnels</option>
                                        <option value="Documents administratifs">Documents administratifs</option>
                                        <option value="Documents médicaux">Documents médicaux</option>
                                        <option value="Documents scolaires">Documents scolaires</option>
                                        <option value="Documents professionnels">Documents professionnels</option>
                                        <option value="Certificats et diplômes">Certificats et diplômes</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                    @error('theme')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description et contenu prévisionnel</label>
                                    <textarea name="description" rows="5" class="form-control"
                                              placeholder="Précisez ici quels types de documents seront rangés dans ce classeur...">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="side-card p-3 mb-3 shadow-sm">
                                    <h6 class="fw-bold mb-3" style="color: var(--secondary-blue)">
                                        <i class="bi bi-lightbulb me-2 text-warning"></i>Bonnes pratiques
                                    </h6>
                                    <ul class="small ps-3 mb-0 text-muted">
                                        <li class="mb-2">Utilisez des noms courts et explicites.</li>
                                        <li class="mb-2">Un classeur doit idéalement regrouper des documents de même nature.</li>
                                        <li>La description aide vos collègues à retrouver les pièces rapidement.</li>
                                    </ul>
                                </div>

                                <div class="card p-3 shadow-sm" id="apercu" style="display:none; border-radius: 10px;">
                                    <div class="small fw-bold text-uppercase text-muted mb-2" style="font-size: 0.65rem;">Aperçu du rendu</div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-folder-fill fs-4 me-2" style="color: var(--secondary-blue)"></i>
                                        <span id="apercu-nom" class="fw-bold text-dark">-</span>
                                    </div>
                                    <div class="small">
                                        <span class="badge bg-white text-dark border fw-normal" id="apercu-type">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light p-4 text-end">
                        <a href="{{ route('menages.dossiers.show', [$menage, $dossier]) }}" class="btn btn-link text-muted text-decoration-none me-3">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-gold px-4 py-2 fw-bold shadow-sm">
                            <i class="bi bi-check-circle me-2"></i>Confirmer la création
                        </button>
                    </div>
                </form>
            </div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const nomInput = document.getElementById('nom');
    const themeSelect = document.getElementById('theme');
    const box = document.getElementById('apercu');

    function updateApercu() {
        const nom = nomInput.value;
        const theme = themeSelect.value;

        if (nom || theme) {
            document.getElementById('apercu-nom').innerText = nom || 'Nouveau classeur';
            document.getElementById('apercu-type').innerText = theme || 'Thème non défini';
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
        }
    }

    nomInput.addEventListener('input', updateApercu);
    themeSelect.addEventListener('change', updateApercu);
});
</script>
@endpush

@endsection