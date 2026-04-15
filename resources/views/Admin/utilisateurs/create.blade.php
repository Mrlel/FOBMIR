@extends('layouts.admin')

@section('content')

<form action="{{ route('users.store') }}" method="POST" id="userForm">
@csrf

<div class="card border-0 p-4">

<h4 class="text-warning mb-4">
    <i class="fas fa-user-plus"></i> Création d’un utilisateur
</h4>

{{-- ===================== INFORMATIONS PERSONNELLES ===================== --}}
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Nom *</label>
        <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Prénom *</label>
        <input type="text" name="prenom" class="form-control" value="{{ old('prenom') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Téléphone *</label>
        <input type="tel" name="telephone" class="form-control" value="{{ old('telephone') }}" required>
    </div>
</div>

<hr class="my-4">

{{-- ===================== LOCALISATION ===================== --}}
<h5 class="text-warning mb-3">
    <i class="fas fa-map-marker-alt"></i> Localisation géographique
</h5>

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Pays *</label>
        <select id="pays_id" class="form-select" required></select>
    </div>

    <div class="col-md-4">
        <label class="form-label">District *</label>
        <select id="district_id" class="form-select" disabled required></select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Région *</label>
        <select id="region_id" class="form-select" disabled required></select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Département *</label>
        <select id="departement_id" class="form-select" disabled required></select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Sous-préfecture *</label>
        <select id="sous_prefecture_id" class="form-select" disabled required></select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Commune (optionnel)</label>
        <select id="commune_id" class="form-select" disabled></select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Village *</label>
        {{-- IMPORTANT : name présent --}}
        <select name="village_id" id="village_id" class="form-select" disabled required></select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Rôle *</label>
        <select name="role" class="form-select" required>
            <option value="">-- Sélectionner --</option>
            <option value="point_focal">Point focal</option>
            <option value="admin">Administrateur</option>
        </select>
    </div>
</div>

{{-- ===================== ACTIONS ===================== --}}
<div class="d-flex justify-content-end mt-4 pt-3 border-top">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save"></i> Enregistrer
    </button>
</div>

</div>
</form>


{{-- ===================== JAVASCRIPT ===================== --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

const form = document.getElementById('userForm');

const selects = {
    pays: document.getElementById('pays_id'),
    district: document.getElementById('district_id'),
    region: document.getElementById('region_id'),
    departement: document.getElementById('departement_id'),
    sp: document.getElementById('sous_prefecture_id'),
    commune: document.getElementById('commune_id'),
    village: document.getElementById('village_id'),
};

const reset = (...elements) => {
    elements.forEach(el => {
        el.innerHTML = '<option value="">-- Sélectionner --</option>';
        el.disabled = true;
    });
};

const load = async (url, select) => {
    const res = await fetch(url);
    const data = await res.json();
    select.innerHTML = '<option value="">-- Sélectionner --</option>';
    data.forEach(item => {
        select.innerHTML += `<option value="${item.id}">${item.nom}</option>`;
    });
    select.disabled = false;
};

const loadWithNonCommunal = async (url, select) => {
    const res = await fetch(url);
    const data = await res.json();
    select.innerHTML = '<option value="">-- Sélectionner --</option>';
    // Option spéciale pour les villages non-communaux
    select.innerHTML += `<option value="non_communal">Secteur non-communal</option>`;
    data.forEach(item => {
        select.innerHTML += `<option value="${item.id}">${item.nom}</option>`;
    });
    select.disabled = false;
};

/* Chargement initial des pays */
load('{{ route("ajax.pays") }}', selects.pays);

/* Enchaînement hiérarchique */
selects.pays.onchange = () => {
    reset(selects.district, selects.region, selects.departement, selects.sp, selects.commune, selects.village);
    if (selects.pays.value)
        load(`/ajax/pays/${selects.pays.value}/districts`, selects.district);
};

selects.district.onchange = () => {
    reset(selects.region, selects.departement, selects.sp, selects.commune, selects.village);
    if (selects.district.value)
        load(`/ajax/districts/${selects.district.value}/regions`, selects.region);
};

selects.region.onchange = () => {
    reset(selects.departement, selects.sp, selects.commune, selects.village);
    if (selects.region.value)
        load(`/ajax/regions/${selects.region.value}/departements`, selects.departement);
};

selects.departement.onchange = () => {
    reset(selects.sp, selects.commune, selects.village);
    if (selects.departement.value)
        load(`/ajax/departements/${selects.departement.value}/sous-prefectures`, selects.sp);
};

selects.sp.onchange = () => {
    reset(selects.commune, selects.village);
    if (selects.sp.value) {
        // Charger les communes avec option "non-communal" en tête
        loadWithNonCommunal(`/ajax/sous-prefectures/${selects.sp.value}/communes`, selects.commune);
        // Par défaut, charger tous les villages (non-communaux uniquement au départ)
        load(`/ajax/sous-prefectures/${selects.sp.value}/villages-non-communaux`, selects.village);
    }
};

selects.commune.onchange = () => {
    reset(selects.village);
    if (selects.commune.value === 'non_communal') {
        // Charger uniquement les villages sans commune
        load(`/ajax/sous-prefectures/${selects.sp.value}/villages-non-communaux`, selects.village);
    } else if (selects.commune.value) {
        // Charger les villages de la commune sélectionnée
        load(`/ajax/communes/${selects.commune.value}/villages`, selects.village);
    }
};

/* 🔥 CORRECTION CRITIQUE : réactiver avant submit */
form.addEventListener('submit', () => {
    selects.village.disabled = false;
});

});
</script>
@endsection
