<div class="mb-3">
    <label class="form-label">Nom</label>
    <input type="text" name="nom" value="{{ old('nom', $individu->nom ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">Prénom</label>
    <input type="text" name="prenom" value="{{ old('prenom', $individu->prenom ?? '') }}" class="form-control">
</div>

<div class="mb-3">
    <label class="form-label">Date de naissance</label>
    <input type="date" name="date_naissance" value="{{ old('date_naissance', $individu->date_naissance ?? '') }}" class="form-control">
</div>

<div class="mb-3">
    <label class="form-label">Qualité dans le ménage</label>
    <input type="text" name="qualite" value="{{ old('qualite', $individu->qualite ?? '') }}" class="form-control">
</div>
