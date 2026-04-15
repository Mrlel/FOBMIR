@extends('layouts.admin')

@section('title', 'Modifier le chef - ' . $chef->prenom . ' ' . $chef->nom)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier le chef de sous-quartier
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('chefs-sous-quartier.show', $chef) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour au chef
                        </a>
                    </div>
                </div>

                <form action="{{ route('chefs-sous-quartier.update', $chef) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prenom">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                                           id="prenom" name="prenom" value="{{ old('prenom', $chef->prenom) }}" required>
                                    @error('prenom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nom">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" value="{{ old('nom', $chef->nom) }}" required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telephone">Téléphone</label>
                                    <input type="tel" class="form-control @error('telephone') is-invalid @enderror" 
                                           id="telephone" name="telephone" value="{{ old('telephone', $chef->telephone) }}">
                                    @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $chef->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sous_quartier_id">Sous-quartier <span class="text-danger">*</span></label>
                                    <select class="form-control @error('sous_quartier_id') is-invalid @enderror" 
                                            id="sous_quartier_id" name="sous_quartier_id" required>
                                        <option value="">Sélectionner un sous-quartier</option>
                                        @foreach($sousQuartiers as $sousQuartier)
                                            <option value="{{ $sousQuartier->id }}" 
                                                    {{ old('sous_quartier_id', $chef->sous_quartier_id) == $sousQuartier->id ? 'selected' : '' }}>
                                                {{ $sousQuartier->nom }} ({{ $sousQuartier->quartier->nom ?? 'Quartier non défini' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sous_quartier_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_nomination">Date de nomination</label>
                                    <input type="date" class="form-control @error('date_nomination') is-invalid @enderror" 
                                           id="date_nomination" name="date_nomination" 
                                           value="{{ old('date_nomination', $chef->date_nomination ? \Carbon\Carbon::parse($chef->date_nomination)->format('Y-m-d') : '') }}">
                                    @error('date_nomination')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="actif" name="actif" value="1" 
                                       {{ old('actif', $chef->actif) ? 'checked' : '' }}>
                                <label class="form-check-label" for="actif">
                                    Chef actif
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                        <a href="{{ route('chefs-sous-quartier.show', $chef) }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection