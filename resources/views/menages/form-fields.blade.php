<div class="row g-3">
    <div class="col-md-12">
        <label class="form-label">Nom du chef</label>
        <input type="text" name="nom_chef" class="form-control" value="{{ $edit ?? false ? '' : old('nom_chef') }}" required>
    </div>
    <div class="col-md-12">
        <label class="form-label">Nombre d'individus</label>
        <input type="number" name="nb_individus" class="form-control" value="{{ $edit ?? false ? '' : old('nb_individus') }}" required>
    </div>
    <div class="col-md-12">
        <label class="form-label">Sexe</label>
        <select name="sexe_chef" class="form-select" required>
            <option value="M" {{ ($edit ?? false) && ($menage->sexe_chef ?? '') == 'M' ? 'selected' : '' }}>Masculin</option>
            <option value="F" {{ ($edit ?? false) && ($menage->sexe_chef ?? '') == 'F' ? 'selected' : '' }}>Féminin</option>
        </select>
    </div>
    <div class="col-md-12">
        <label class="form-label">Sous-Quartier</label>
        <select name="sous_quartier_id" class="form-select" required>
            <option value="">-- Sélectionner --</option>
            @foreach($sousQuartiers as $sq)
                <option value="{{ $sq->id }}" {{ ($edit ?? false) && ($menage->sous_quartier_id ?? '') == $sq->id ? 'selected' : '' }}>
                    {{ $sq->nom }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-12">
        <label class="form-label">Origine</label>
        <select name="origine_id" class="form-select" required>
            <option value="">-- Sélectionner --</option>
            @foreach($origines as $orig)
                <option value="{{ $orig->id }}" {{ ($edit ?? false) && ($menage->origine_id ?? '') == $orig->id ? 'selected' : '' }}>
                    {{ $orig->libelle }}
                </option>
            @endforeach
        </select>
    </div>
</div>