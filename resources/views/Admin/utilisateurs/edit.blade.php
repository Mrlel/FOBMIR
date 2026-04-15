@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h1 class="h3 mb-0 text-gray-800">Modifier l’utilisateur</h1>
        <a href="/admin/utilisateurs" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    @include('layouts.message')

    <!-- Carte formulaire -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Identité -->
                <h6 class="mb-3 text-muted">Identité</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $user->nom) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                        <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom', $user->prenom) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                        <input type="tel" name="telephone" id="telephone" class="form-control" value="{{ old('telephone', $user->telephone) }}" required>
                    </div>
                </div>

                <!-- Attribution géographique (sélection hiérarchique) -->
                <h6 class="mb-3 text-muted">Localisation et rattachement</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Pays</label>
                        <select id="pays_id" class="form-select"></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">District</label>
                        <select id="district_id" class="form-select"></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Région</label>
                        <select id="region_id" class="form-select"></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Département</label>
                        <select id="departement_id" class="form-select"></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sous-préfecture</label>
                        <select id="sous_prefecture_id" class="form-select"></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Commune (optionnel)</label>
                        <select id="commune_id" class="form-select"></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Village</label>
                        <select id="village_id" class="form-select"></select>
                    </div>
                    <div class="col-md-6">
                        <label for="role" class="form-label">Rôle</label>
                        <select name="role" id="role" class="form-select">
                            <option value="point_focal" {{ old('role', $user->role) == 'point_focal' ? 'selected' : '' }}>Point focal</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                        </select>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preview fichier + AJAX init chain -->
<script>
function previewFile() {
    const file = document.getElementById('doc_piece').files[0];
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    if (file && file.type.startsWith('image/')) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.className = 'img-thumbnail mt-2';
        img.width = 150;
        preview.appendChild(img);
    } else if (file && file.type === 'application/pdf') {
        preview.innerHTML = '<span class="badge bg-info">Nouveau PDF chargé</span>';
    }
}

async function fetchJson(url) {
    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
    if (!res.ok) throw new Error('Erreur');
    return await res.json();
}

function fillSelect(select, items, placeholder = '-- Sélectionner --', selectedId = null) {
    select.innerHTML = '';
    const opt = document.createElement('option');
    opt.value = '';
    opt.textContent = placeholder;
    select.appendChild(opt);
    items.forEach(i => {
        const o = document.createElement('option');
        o.value = i.id;
        o.textContent = i.nom;
        if (selectedId && Number(selectedId) === Number(i.id)) o.selected = true;
        select.appendChild(o);
    });
    select.disabled = false;
}

function resetDownstream(ids) {
    ids.forEach(id => {
        const el = document.getElementById(id);
        if (el) { el.innerHTML = '<option value="">-- Sélectionner --</option>'; el.disabled = true; }
    });
}

document.addEventListener('DOMContentLoaded', async () => {
    const chain = @json($chain ?? []);
    const pays = document.getElementById('pays_id');
    const district = document.getElementById('district_id');
    const region = document.getElementById('region_id');
    const dep = document.getElementById('departement_id');
    const sp = document.getElementById('sous_prefecture_id');
    const commune = document.getElementById('commune_id');
    const village = document.getElementById('village_id');
    const quartier = document.getElementById('quartier_id');
    const sq = document.getElementById('sous_quartier_id');
    const menage = document.getElementById('menage_id');

    // Pays
    const paysList = await fetchJson('{{ route('ajax.pays') }}');
    fillSelect(pays, paysList, '-- Sélectionner un pays --', chain.pays_id);
    
    // Districts
    if (chain.pays_id) {
        const ds = await fetchJson(`{{ url('/ajax/pays') }}/${chain.pays_id}/districts`);
        fillSelect(district, ds, '-- Sélectionner un district --', chain.district_id);
    }

    // Regions
    if (chain.district_id) {
        const rs = await fetchJson(`{{ url('/ajax/districts') }}/${chain.district_id}/regions`);
        fillSelect(region, rs, '-- Sélectionner une région --', chain.region_id);
    }

    // Departements
    if (chain.region_id) {
        const deps = await fetchJson(`{{ url('/ajax/regions') }}/${chain.region_id}/departements`);
        fillSelect(dep, deps, '-- Sélectionner un département --', chain.departement_id);
    }

    // Sous-prefectures
    if (chain.departement_id) {
        const sps = await fetchJson(`{{ url('/ajax/departements') }}/${chain.departement_id}/sous-prefectures`);
        fillSelect(sp, sps, '-- Sélectionner une sous-préfecture --', chain.sous_prefecture_id);
    }

    // Communes + Villages
    if (chain.sous_prefecture_id) {
        const vs = await fetchJson(`{{ url('/ajax/sous-prefectures') }}/${chain.sous_prefecture_id}/villages-non-communaux`);
        fillSelect(village, vs, '-- Sélectionner un village --', chain.village_id);
        const cs = await fetchJson(`{{ url('/ajax/sous-prefectures') }}/${chain.sous_prefecture_id}/communes`);
        // Ajouter l'option non-communal en tête
        const nonCommunalOpt = { id: 'non_communal', nom: '🌿 Secteur non-communal' };
        fillSelect(commune, [nonCommunalOpt, ...cs], '-- (Optionnel) Sélectionner une commune --', chain.commune_id ?? (chain.village_id && !chain.commune_id ? 'non_communal' : null));
        // Si une commune est sélectionnée, recharger les villages de cette commune
        if (chain.commune_id) {
            const vsCommune = await fetchJson(`{{ url('/ajax/communes') }}/${chain.commune_id}/villages`);
            fillSelect(village, vsCommune, '-- Sélectionner un village --', chain.village_id);
        }
    }

    // Quartiers
    if (chain.village_id) {
        const qs = await fetchJson(`{{ url('/ajax/villages') }}/${chain.village_id}/quartiers`);
        fillSelect(quartier, qs, '-- Sélectionner un quartier --', chain.quartier_id);
    }

    // Sous-quartiers
    if (chain.quartier_id) {
        const sqs = await fetchJson(`{{ url('/ajax/quartiers') }}/${chain.quartier_id}/sous-quartiers`);
        fillSelect(sq, sqs, '-- Sélectionner un sous-quartier --', chain.sous_quartier_id);
    }

    // Menages
    if (chain.sous_quartier_id) {
        const ms = await fetchJson(`{{ url('/ajax/sous-quartiers') }}/${chain.sous_quartier_id}/menages`);
        fillSelect(menage, ms, '-- Sélectionner un ménage --', chain.menage_id);
        menage.required = true;
    }

    // Ecouteurs cascades (mêmes que create)
    pays.addEventListener('change', async () => {
        resetDownstream(['district_id','region_id','departement_id','sous_prefecture_id','commune_id','village_id','quartier_id','sous_quartier_id','menage_id']);
        if (!pays.value) return;
        const data = await fetchJson(`{{ url('/ajax/pays') }}/${pays.value}/districts`);
        fillSelect(district, data, '-- Sélectionner un district --');
    });
    district.addEventListener('change', async () => {
        resetDownstream(['region_id','departement_id','sous_prefecture_id','commune_id','village_id','quartier_id','sous_quartier_id','menage_id']);
        if (!district.value) return;
        const data = await fetchJson(`{{ url('/ajax/districts') }}/${district.value}/regions`);
        fillSelect(region, data, '-- Sélectionner une région --');
    });
    region.addEventListener('change', async () => {
        resetDownstream(['departement_id','sous_prefecture_id','commune_id','village_id','quartier_id','sous_quartier_id','menage_id']);
        if (!region.value) return;
        const data = await fetchJson(`{{ url('/ajax/regions') }}/${region.value}/departements`);
        fillSelect(dep, data, '-- Sélectionner un département --');
    });
    dep.addEventListener('change', async () => {
        resetDownstream(['sous_prefecture_id','commune_id','village_id','quartier_id','sous_quartier_id','menage_id']);
        if (!dep.value) return;
        const data = await fetchJson(`{{ url('/ajax/departements') }}/${dep.value}/sous-prefectures`);
        fillSelect(sp, data, '-- Sélectionner une sous-préfecture --');
    });
    sp.addEventListener('change', async () => {
        resetDownstream(['commune_id','village_id','quartier_id','sous_quartier_id','menage_id']);
        if (!sp.value) return;
        const cs = await fetchJson(`{{ url('/ajax/sous-prefectures') }}/${sp.value}/communes`);
        const nonCommunalOpt = { id: 'non_communal', nom: '🌿 Secteur non-communal' };
        fillSelect(commune, [nonCommunalOpt, ...cs], '-- (Optionnel) Sélectionner une commune --');
        const vs = await fetchJson(`{{ url('/ajax/sous-prefectures') }}/${sp.value}/villages-non-communaux`);
        fillSelect(village, vs, '-- Sélectionner un village --');
    });
    commune.addEventListener('change', async () => {
        resetDownstream(['village_id','quartier_id','sous_quartier_id','menage_id']);
        if (!commune.value) return;
        if (commune.value === 'non_communal') {
            const vs = await fetchJson(`{{ url('/ajax/sous-prefectures') }}/${sp.value}/villages-non-communaux`);
            fillSelect(village, vs, '-- Sélectionner un village --');
        } else {
            const vs = await fetchJson(`{{ url('/ajax/communes') }}/${commune.value}/villages`);
            fillSelect(village, vs, '-- Sélectionner un village --');
        }
    });
    village.addEventListener('change', async () => {
        resetDownstream(['quartier_id','sous_quartier_id','menage_id']);
        if (!village.value) return;
        const qs = await fetchJson(`{{ url('/ajax/villages') }}/${village.value}/quartiers`);
        fillSelect(quartier, qs, '-- Sélectionner un quartier --');
    });
    quartier.addEventListener('change', async () => {
        resetDownstream(['sous_quartier_id','menage_id']);
        if (!quartier.value) return;
        const sqs = await fetchJson(`{{ url('/ajax/quartiers') }}/${quartier.value}/sous-quartiers`);
        fillSelect(sq, sqs, '-- Sélectionner un sous-quartier --');
    });
    sq.addEventListener('change', async () => {
        menage.innerHTML = '<option value="">-- Sélectionner un ménage --</option>';
        menage.disabled = true;
        if (!sq.value) return;
        const ms = await fetchJson(`{{ url('/ajax/sous-quartiers') }}/${sq.value}/menages`);
        fillSelect(menage, ms, '-- Sélectionner un ménage --');
        menage.required = true;
    });
});
</script>
@endsection