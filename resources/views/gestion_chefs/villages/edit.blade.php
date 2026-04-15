@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Modifier le chef de village</h1>

    <form action="{{ route('chefs-village.update', $chefVillage) }}" method="POST" class="mt-4">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom" class="form-label">Nom *</label>
            <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $chefVillage->nom) }}" required>
            @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="sexe" class="form-label">Sexe *</label>
            <select name="sexe" id="sexe" class="form-select @error('sexe') is-invalid @enderror" required>
                <option value="M" {{ old('sexe', $chefVillage->sexe)=='M' ? 'selected' : '' }}>Masculin</option>
                <option value="F" {{ old('sexe', $chefVillage->sexe)=='F' ? 'selected' : '' }}>Féminin</option>
            </select>
            @error('sexe') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="village_id" class="form-label">Village *</label>
            <select name="village_id" id="village_id" class="form-select @error('village_id') is-invalid @enderror" required>
                @foreach($villages as $village)
                    <option value="{{ $village->id }}" {{ old('village_id', $chefVillage->village_id)==$village->id ? 'selected' : '' }}>{{ $village->nom }}</option>
                @endforeach
            </select>
            @error('village_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="debut_mandat" class="form-label">Début mandat</label>
                <input type="date" name="debut_mandat" id="debut_mandat" class="form-control @error('debut_mandat') is-invalid @enderror" value="{{ old('debut_mandat', $chefVillage->debut_mandat?->format('Y-m-d')) }}">
                @error('debut_mandat') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label for="fin_mandat" class="form-label">Fin mandat</label>
                <input type="date" name="fin_mandat" id="fin_mandat" class="form-control @error('fin_mandat') is-invalid @enderror" value="{{ old('fin_mandat', $chefVillage->fin_mandat?->format('Y-m-d')) }}">
                @error('fin_mandat') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="num_arrete_nomination" class="form-label">N° arrêté nomination</label>
            <input type="text" name="num_arrete_nomination" id="num_arrete_nomination" class="form-control @error('num_arrete_nomination') is-invalid @enderror" value="{{ old('num_arrete_nomination', $chefVillage->num_arrete_nomination) }}" maxlength="25">
            @error('num_arrete_nomination') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('chefs-village.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection