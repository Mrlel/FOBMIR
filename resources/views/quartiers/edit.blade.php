@extends('layouts.admin')

@section('content')
    <h1>Modifier le quartier</h1>
    <form action="{{ route('quartiers.update', $quartier->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control" value="{{ $quartier->nom }}" required>
        </div>
        <div class="form-group">
            <label>Historique</label>
            <textarea name="historique" class="form-control">{{ $quartier->historique }}</textarea>
        </div>
        <div class="form-group">
            <label>Village</label>
            <select name="village_id" class="form-control" required>
                @foreach($villages as $village)
                    <option value="{{ $village->id }}" {{ $quartier->village_id == $village->id ? 'selected' : '' }}>
                        {{ $village->nom }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Type de quartier</label>
            <select name="type_quartier_id" class="form-control">
                <option value="">-- Sélectionner --</option>
                @foreach($typeQuartiers as $type)
                    <option value="{{ $type->id }}" {{ $quartier->type_quartier_id == $type->id ? 'selected' : '' }}>
                        {{ $type->libelle }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
@endsection
