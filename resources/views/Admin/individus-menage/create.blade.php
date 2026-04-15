@extends('layouts.admin')

@section('content')
<style>
    :root {
        --primary: #f59e0b;
        --primary-dark: #d97706;
        --secondary: #1e3a5f;
        --dark: #0d1b2a;
        --light: #f1f5f9;
    }

    body { background-color: var(--light) !important; }

    /* Cards Styling */
    .card { border: none; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden; }
    .card-header { border-bottom: none; padding: 1.25rem; font-weight: 600; }
    .card-header i { margin-right: 10px; }
    
    /* Input Styling */
    .form-label { font-weight: 600; color: var(--secondary); font-size: 0.9rem; }
    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        padding: 0.6rem 0.8rem;
        border-radius: 8px;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.25 row rgba(245, 158, 11, 0.25);
    }

    /* Buttons */
    .btn-primary { background-color: var(--primary); border: none; padding: 0.6rem 1.5rem; border-radius: 8px; }
    .btn-primary:hover { background-color: var(--primary-dark); }
    .btn-secondary { background-color: var(--secondary); border: none; border-radius: 8px; }
    .btn-success { background-color: #10b981; border: none; } /* Émeraude pour le succès */

    /* Custom labels for disabled selects */
    select:disabled { background-color: #f8fafc !important; cursor: not-allowed; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-bold mb-0 text-dark">
            <i class="bi bi-person-plus-fill text-primary"></i> Créer un Nouvel Individu
        </h2>
        <a href="{{ route('admin.individus-menage.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <div class="fw-bold"><i class="bi bi-exclamation-octagon-fill me-2"></i> Veuillez corriger les erreurs suivantes :</div>
            <ul class="mt-2 mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.individus-menage.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 text-primary"><i class="bi bi-person-badge"></i> Informations Personnelles</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom" class="form-control" placeholder="Entrez le nom" value="{{ old('nom') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="prenom" class="form-control" placeholder="Entrez le prénom" value="{{ old('prenom') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                                <input type="text" name="lieu_naissance" class="form-control" placeholder="Lieu de naissance" value="{{ old('lieu_naissance') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Téléphone</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-phone"></i></span>
                                    <input type="tel" name="telephone" class="form-control" placeholder="Ex: 0102030405" value="{{ old('telephone') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Emploi / Profession</label>
                                <input type="text" name="emploi" class="form-control" placeholder="Profession actuelle" value="{{ old('emploi') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">N° Pièce d'identité <span class="text-danger">*</span></label>
                                <input type="text" name="numpiece" class="form-control" placeholder="CNI, Passeport..." value="{{ old('numpiece') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">N° Extrait de naissance <span class="text-danger">*</span></label>
                                <input type="text" name="num_extrait_naissance" class="form-control" placeholder="Numéro d'acte" value="{{ old('num_extrait_naissance') }}" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Document justificatif (Pièce/Extrait)</label>
                                <input type="file" name="doc_piece" class="form-control" accept="image/*,application/pdf">
                                <div class="form-text">Formats acceptés : JPG, PNG, PDF (Max 2Mo)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white text-info">
                        <h5 class="mb-0"><i class="bi bi-house-heart"></i> Ménage et Rattachement</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Sélectionner le Ménage <span class="text-danger">*</span></label>
                                <select name="menage_id" id="menage_id" class="form-select border-info" required disabled>
                                    <option value="">-- Sélectionnez d'abord une localisation --</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-outline-success w-100 fw-bold" id="btn-create-menage" disabled>
                                    <i class="bi bi-plus-circle-fill"></i> Nouveau Ménage
                                </button>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="form-label">Point Focal Responsable</label>
                                <select name="point_focal_id" class="form-select">
                                    <option value="">-- Utiliser le SuperAdmin par défaut --</option>
                                    @foreach($pointsFocaux as $pf)
                                        <option value="{{ $pf->id }}" {{ old('point_focal_id') == $pf->id ? 'selected' : '' }}>
                                            {{ $pf->prenom }} {{ $pf->nom }} ({{ $pf->village->nom ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white text-dark">
                        <h5 class="mb-0 text-warning-emphasis"><i class="bi bi-geo-alt-fill"></i> Localisation</h5>
                    </div>
                    <div class="card-body bg-light-subtle">
                        <div class="mb-3">
                            <label class="form-label">Pays <span class="text-danger">*</span></label>
                            <select name="pays_id" id="pays_id" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($pays as $p)
                                    <option value="{{ $p->id }}" {{ old('pays_id') == $p->id ? 'selected' : '' }}>{{ $p->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">District</label>
                            <select name="district_id" id="district_id" class="form-select" disabled></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Région</label>
                            <select name="region_id" id="region_id" class="form-select" disabled></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Département</label>
                            <select name="departement_id" id="departement_id" class="form-select" disabled></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sous-Préfecture</label>
                            <select name="sous_prefecture_id" id="sous_prefecture_id" class="form-select" disabled></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Commune</label>
                            <select name="commune_id" id="commune_id" class="form-select" disabled></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Village</label>
                            <select name="village_id" id="village_id" class="form-select" disabled></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quartier</label>
                            <select name="quartier_id" id="quartier_id" class="form-select" disabled></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-primary fw-bold">Sous-Quartier</label>
                            <select name="sous_quartier_id" id="sous_quartier_id" class="form-select border-primary shadow-sm" disabled></select>
                        </div>
                    </div>
                </div>

                <div class="card p-3 shadow-sm border-0 bg-white">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold text-white shadow">
                            <i class="bi bi-save2-fill me-2"></i> ENREGISTRER
                        </button>
                        <a href="{{ route('admin.individus-menage.index') }}" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="modalCreateMenage" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-house-add"></i> Nouveau Ménage Rapide</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label">Nom complet du chef <span class="text-danger">*</span></label>
                    <input type="text" id="menage_nom_chef" class="form-control form-control-lg" placeholder="Ex: Jean Kouassi" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sexe du chef</label>
                        <select id="menage_sexe_chef" class="form-select">
                            <option value="">-- Optionnel --</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre d'individus</label>
                        <input type="number" id="menage_nb_individus" class="form-control" min="1" value="1">
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-success px-4 fw-bold" onclick="createMenage()">
                    <i class="bi bi-check-lg"></i> Créer le ménage
                </button>
            </div>
        </div>
    </div>
</div>


<script>
// Cascade géolocalisation
async function loadOptions(url, selectId) {
    try {
        const res = await fetch(url);
        const data = await res.json();
        const select = document.getElementById(selectId);
        select.innerHTML = '<option value="">-- Sélectionner --</option>';
        data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.nom;
            select.appendChild(opt);
        });
        select.disabled = false;
    } catch(e) {
        console.error('Erreur chargement:', e);
    }
}

async function loadWithNonCommunal(url, selectId) {
    try {
        const res = await fetch(url);
        const data = await res.json();
        const select = document.getElementById(selectId);
        select.innerHTML = '<option value="">-- Sélectionner --</option>';
        // Option spéciale pour les villages sans commune
        const nonCom = document.createElement('option');
        nonCom.value = 'non_communal';
        nonCom.textContent = 'Secteur non-communal';
        select.appendChild(nonCom);
        data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.nom;
            select.appendChild(opt);
        });
        select.disabled = false;
    } catch(e) {
        console.error('Erreur chargement:', e);
    }
}

function resetSelects(ids) {
    ids.forEach(id => {
        const el = document.getElementById(id);
        el.innerHTML = '<option value="">-- Sélectionner --</option>';
        el.disabled = true;
    });
}

document.getElementById('pays_id').addEventListener('change', function() {
    const paysId = this.value;
    resetSelects(['district_id', 'region_id', 'departement_id', 'sous_prefecture_id', 'commune_id', 'village_id', 'quartier_id', 'sous_quartier_id', 'menage_id']);
    if (paysId) loadOptions(`/admin/ajax/pays/${paysId}/districts`, 'district_id');
});

document.getElementById('district_id').addEventListener('change', function() {
    const districtId = this.value;
    resetSelects(['region_id', 'departement_id', 'sous_prefecture_id', 'commune_id', 'village_id', 'quartier_id', 'sous_quartier_id', 'menage_id']);
    if (districtId) loadOptions(`/admin/ajax/districts/${districtId}/regions`, 'region_id');
});

document.getElementById('region_id').addEventListener('change', function() {
    const regionId = this.value;
    resetSelects(['departement_id', 'sous_prefecture_id', 'commune_id', 'village_id', 'quartier_id', 'sous_quartier_id', 'menage_id']);
    if (regionId) loadOptions(`/admin/ajax/regions/${regionId}/departements`, 'departement_id');
});

document.getElementById('departement_id').addEventListener('change', function() {
    const deptId = this.value;
    resetSelects(['sous_prefecture_id', 'commune_id', 'village_id', 'quartier_id', 'sous_quartier_id', 'menage_id']);
    if (deptId) loadOptions(`/admin/ajax/departements/${deptId}/sous-prefectures`, 'sous_prefecture_id');
});

document.getElementById('sous_prefecture_id').addEventListener('change', function() {
    const spId = this.value;
    resetSelects(['commune_id', 'village_id', 'quartier_id', 'sous_quartier_id', 'menage_id']);
    if (spId) {
        // Charger communes avec option non-communal
        loadWithNonCommunal(`/admin/ajax/sous-prefectures/${spId}/communes`, 'commune_id');
        // Par défaut : villages non-communaux
        loadOptions(`/admin/ajax/sous-prefectures/${spId}/villages-non-communaux`, 'village_id');
    }
});

document.getElementById('commune_id').addEventListener('change', function() {
    const communeId = this.value;
    const spId = document.getElementById('sous_prefecture_id').value;
    resetSelects(['village_id', 'quartier_id', 'sous_quartier_id', 'menage_id']);
    if (communeId === 'non_communal') {
        loadOptions(`/admin/ajax/sous-prefectures/${spId}/villages-non-communaux`, 'village_id');
    } else if (communeId) {
        loadOptions(`/admin/ajax/communes/${communeId}/villages`, 'village_id');
    }
});

document.getElementById('village_id').addEventListener('change', function() {
    const villageId = this.value;
    resetSelects(['quartier_id', 'sous_quartier_id', 'menage_id']);
    if (villageId) loadOptions(`/admin/ajax/villages/${villageId}/quartiers`, 'quartier_id');
});

document.getElementById('quartier_id').addEventListener('change', function() {
    const quartierId = this.value;
    resetSelects(['sous_quartier_id', 'menage_id']);
    if (quartierId) loadOptions(`/admin/ajax/quartiers/${quartierId}/sous-quartiers`, 'sous_quartier_id');
});

document.getElementById('sous_quartier_id').addEventListener('change', function() {
    const sqId = this.value;
    resetSelects(['menage_id']);
    if (sqId) {
        loadOptions(`/admin/ajax/sous-quartiers/${sqId}/menages`, 'menage_id');
        document.getElementById('btn-create-menage').disabled = false;
    } else {
        document.getElementById('btn-create-menage').disabled = true;
    }
});

// Création rapide ménage
document.getElementById('btn-create-menage').addEventListener('click', function() {
    new bootstrap.Modal(document.getElementById('modalCreateMenage')).show();
});

async function createMenage() {
    const nomChef = document.getElementById('menage_nom_chef').value.trim();
    const sousQuartierId = document.getElementById('sous_quartier_id').value;
    
    if (!nomChef || !sousQuartierId) {
        alert('Veuillez remplir le nom du chef');
        return;
    }

    try {
        const res = await fetch('/admin/ajax/menages/create-rapide', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                nom_chef: nomChef,
                sous_quartier_id: sousQuartierId,
                sexe_chef: document.getElementById('menage_sexe_chef').value,
                nb_individus: document.getElementById('menage_nb_individus').value
            })
        });

        const data = await res.json();
        if (data.success) {
            const select = document.getElementById('menage_id');
            const opt = document.createElement('option');
            opt.value = data.menage.id;
            opt.textContent = data.menage.nom_chef;
            opt.selected = true;
            select.appendChild(opt);
            
            bootstrap.Modal.getInstance(document.getElementById('modalCreateMenage')).hide();
            alert('Ménage créé avec succès');
        }
    } catch(e) {
        alert('Erreur lors de la création');
        console.error(e);
    }
}
</script>
@endsection