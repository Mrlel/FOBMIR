@extends('layouts.admin')

@section('content')
    <h1>Ajouter un quartier</h1>
    <form action="{{ route('quartiers.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Historique</label>
            <textarea name="historique" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label>Village</label>
            <select name="village_id" class="form-control" required>
                @foreach($villages as $village)
                    <option value="{{ $village->id }}">{{ $village->nom }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Type de quartier</label>
            <select name="type_quartier_id" class="form-control">
                <option value="">-- Sélectionner --</option>
                @foreach($typeQuartiers as $type)
                    <option value="{{ $type->id }}">{{ $type->libelle }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
@endsection
