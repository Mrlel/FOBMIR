@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-pencil"></i> Modifier l'Individu Ménage</h2>
        <a href="{{ route('admin.individus-menage.show', $individuMenage) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.individus-menage.update', $individuMenage) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Informations personnelles -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person"></i> Informations Personnelles</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" value="{{ old('nom', $individuMenage->nom) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prénom <span class="text-danger">*</span></label>
                        <input type="text" name="prenom" class="form-control" value="{{ old('prenom', $individuMenage->prenom) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                        <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', $individuMenage->date_naissance) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                        <input type="text" name="lieu_naissance" class="form-control" value="{{ old('lieu_naissance', $individuMenage->lieu_naissance) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" name="telephone" class="form-control" value="{{ old('telephone', $individuMenage->telephone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Emploi</label>
                        <input type="text" name="emploi" class="form-control" value="{{ old('emploi', $individuMenage->emploi) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">N° Pièce <span class="text-danger">*</span></label>
                        <input type="text" name="numpiece" class="form-control" value="{{ old('numpiece', $individuMenage->numpiece) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">N° Extrait de naissance <span class="text-danger">*</span></label>
                        <input type="text" name="num_extrait_naissance" class="form-control" value="{{ old('num_extrait_naissance', $individuMenage->num_extrait_naissance) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Document de pièce</label>
                        <input type="file" name="doc_piece" class="form-control" accept="image/*,application/pdf">
                        @if($individuMenage->doc_piece)
                            <small class="text-muted">
                                Document actuel: <a href="{{ asset('storage/' . $individuMenage->doc_piece) }}" target="_blank">Voir</a>
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Géolocalisation -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Géolocalisation Complète</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Pays <span class="text-danger">*</span></label>
                        <select name="pays_id" id="pays_id" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach($pays as $p)
                                @php
                                    $paysId = $individuMenage->menage->sousQuartier->quartier->village->sousPrefecture->departement->region->district->pays_id ?? null;
                                @endphp
                                <option value="{{ $p->id }}" {{ old('pays_id', $paysId) == $p->id ? 'selected' : '' }}>{{ $p->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">District</label>
                        <select name="district_id" id="district_id" class="form-select">
                            <option value="">-- Sélectionner --</option>
                            @foreach($districts as $d)
                                @php
                                    $districtId = $individuMenage->menage->sousQuartier->quartier->village->sousPrefecture->departement->region->district_id ?? null;
                                @endphp
                                <option value="{{ $d->id }}" {{ old('district_id', $districtId) == $d->id ? 'selected' : '' }}>{{ $d->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Région</label>
                        <select name="region_id" id="region_id" class="form-select">
                            <option value="">-- Sélectionner --</option>
                            @foreach($regions as $r)
                                @php
                                    $regionId = $individuMenage->menage->sousQuartier->quartier->village->sousPrefecture->departement->region_id ?? null;
                                @endphp
                                <option value="{{ $r->id }}" {{ old('region_id', $regionId) == $r->id ? 'selected' : '' }}>{{ $r->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Département</label>
                        <select name="departement_id" id="departement_id" class="form-select">
                            <option value="">-- Sélectionner --</option>
                            @foreach($departements as $dept)
                                @php
                                    $deptId = $individuMenage->menage->sousQuartier->quartier->village->sousPrefecture->departement_id ?? null;
                                @endphp
                                <option value="{{ $dept->id }}" {{ old('departement_id', $deptId) == $dept->id ? 'selected' : '' }}>{{ $dept->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sous-Préfecture</label>
                        <select name="sous_prefecture_id" id="sous_prefecture_id" class="form-select">
                            <option value="">-- Sélectionner --</option>
                            @foreach($sousPrefectures as $sp)
                                @php
                                    $spId = $individuMenage->menage->sousQuartier->quartier->village->sous_prefecture_id ?? null;
                                @endphp
                                <option value="{{ $sp->id }}" {{ old('sous_prefecture_id', $spId) == $sp->id ? 'selected' : '' }}>{{ $sp->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Commune</label>
                        <select name="commune_id" id="commune_id" class="form-select">
                            <option value="">-- Sélectionner --</option>
                            @foreach($communes as $c)
                                @php
                                    $communeId = $individuMenage->menage->sousQuartier->quartier->village->commune_id ?? null;
                                @endphp
                                <option value="{{ $c->id }}" {{ old('commune_id', $communeId) == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Village</label>
                        <select name="village_id" id="village_id" class="form-select">
                            <option value="">-- Sélectionner --</option>
                            @foreach($villages as $v)
                                @php
                                    $villageId = $individuMenage->menage->sousQuartier->quartier->village_id ?? null;
                                @endphp
                                <option value="{{ $v->id }}" {{ old('village_id', $villageId) == $v->id ? 'selected' : '' }}>{{ $v->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Quartier</label>
                        <select name="quartier_id" id="quartier_id" class="form-select">
                            <option value="">-- Sélectionner --</option>
                            @foreach($quartiers as $q)
                                @php
                                    $quartierId = $individuMenage->menage->sousQuartier->quartier_id ?? null;
                                @endphp
                                <option value="{{ $q->id }}" {{ old('quartier_id', $quartierId) == $q->id ? 'selected' : '' }}>{{ $q->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sous-Quartier</label>
                        <select name="sous_quartier_id" id="sous_quartier_id" class="form-select">
                            <option value="">-- Sélectionner --</option>
                            @foreach($sousQuartiers as $sq)
                                @php
                                    $sqId = $individuMenage->menage->sous_quartier_id ?? null;
                                @endphp
                                <option value="{{ $sq->id }}" {{ old('sous_quartier_id', $sqId) == $sq->id ? 'selected' : '' }}>{{ $sq->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ménage et Point Focal -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-house-door"></i> Ménage et Point Focal</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Ménage <span class="text-danger">*</span></label>
                        <select name="menage_id" id="menage_id" class="form-select" required>
                            <option value="">-- Sélectionner un ménage --</option>
                            @foreach($menages as $m)
                                <option value="{{ $m->id }}" {{ old('menage_id', $individuMenage->menage_id) == $m->id ? 'selected' : '' }}>
                                    {{ $m->nom_chef }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Point Focal</label>
                        <select name="point_focal_id" class="form-select">
                            <option value="">-- Par défaut: SuperAdmin --</option>
                            @foreach($pointsFocaux as $pf)
                                <option value="{{ $pf->id }}" {{ old('point_focal_id', $individuMenage->point_focal_id) == $pf->id ? 'selected' : '' }}>
                                    {{ $pf->prenom }} {{ $pf->nom }} ({{ $pf->village->nom ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.individus-menage.show', $individuMenage) }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<script>
// Cascade géolocalisation (même logique que create.blade.php)
async function loadOptions(url, selectId) {
    try {
        const res = await fetch(url);
        const data = await res.json();
        const select = document.getElementById(selectId);
        const currentValue = select.value;
        select.innerHTML = '<option value="">-- Sélectionner --</option>';
        data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.nom;
            if (item.id == currentValue) opt.selected = true;
            select.appendChild(opt);
        });
        select.disabled = false;
    } catch(e) {
        console.error('Erreur chargement:', e);
    }
}

async function loadWithNonCommunal(url, selectId, selectedValue = null) {
    try {
        const res = await fetch(url);
        const data = await res.json();
        const select = document.getElementById(selectId);
        select.innerHTML = '<option value="">-- Sélectionner --</option>';
        const nonCom = document.createElement('option');
        nonCom.value = 'non_communal';
        nonCom.textContent = '🌿 Secteur non-communal';
        if (selectedValue === 'non_communal') nonCom.selected = true;
        select.appendChild(nonCom);
        data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.nom;
            if (selectedValue && item.id == selectedValue) opt.selected = true;
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
        const currentValue = el.value;
        el.innerHTML = '<option value="">-- Sélectionner --</option>';
        el.disabled = true;
        el.setAttribute('data-current', currentValue);
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
        loadWithNonCommunal(`/admin/ajax/sous-prefectures/${spId}/communes`, 'commune_id');
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
    if (sqId) loadOptions(`/admin/ajax/sous-quartiers/${sqId}/menages`, 'menage_id');
});
</script>
@endsection
