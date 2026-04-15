@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h1 class="h3 mb-0 text-gray-800">Liste des chefs de quartier</h1>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ChefQuartierModal">
            Ajouter un chef de quartier
        </button>
    </div>

    <!-- Barre de recherche -->
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par nom, sexe, quartier...">
    </div>
    <div class="mb-3">
      <h5>Total : {{ $chefs->count() }}</h5>
    </div>

    <table class="table responsive">
        <thead class="table-dark">
            <tr>
                <th>Nom</th>
                <th>Sexe</th>
                <th>Quartier</th>
                <th>Début mandat</th>
                <th>Fin mandat</th>
                <th width="120">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($chefs as $chef)
                <tr>
                    <td>{{ $chef->nom }}</td>
                    <td>@if($chef->sexe == 'M') Masculin @else Feminin @endif</td>
                    <td>{{ $chef->quartier->nom }}</td>
                    <td>{{ $chef->debut_mandat?->format('d/m/Y') }}</td>
                    <td>{{ $chef->fin_mandat?->format('d/m/Y') }}</td>
                    <td>
                        <!-- Bouton Modifier (ouvre le modal de modification) -->
                        <button type="button" class="btn btn-sm shadow-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modifChefQuartierModal-{{ $chef->id }}">
                            <i class="bi bi-pencil text-primary"></i>
                        </button>
                        <!-- Formulaire de suppression -->
                        <form action="{{ route('chefs-quartier.destroy', $chef->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm shadow-sm "><i class="bi bi-trash text-danger"></i></button>
                        </form>
                    </td>
                </tr>

                 <!-- Modal pour la MODIFICATION -->
    <div class="modal fade" id="modifChefQuartierModal-{{ $chef->id }}" tabindex="-1" aria-labelledby="modifChefQuartierModalLabel-{{ $chef->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modifChefQuartierModalLabel-{{ $chef->id }}">Modifier le chef de quartier</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('chefs-quartier.update', $chef->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="{{ $chef->nom }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="sexe" class="form-label">Sexe</label>
                            <select class="form-select" id="sexe" name="sexe" required>
                                <option value="M" {{ $chef->sexe == 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ $chef->sexe == 'F' ? 'selected' : '' }}>Féminin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="debut_mandat" class="form-label">Début mandat</label>
                            <input type="date" class="form-control" id="debut_mandat" name="debut_mandat"
                                   value="{{ $chef->debut_mandat?->format('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="fin_mandat" class="form-label">Fin mandat</label>
                            <input type="date" class="form-control" id="fin_mandat" name="fin_mandat"
                                   value="{{ $chef->fin_mandat?->format('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="quartier_id" class="form-label">Quartier</label>
                            <select class="form-select" id="quartier_id" name="quartier_id" required>
                                @foreach($quartiers as $quartier)
                                    <option value="{{ $quartier->id }}"
                                        {{ $chef->quartier_id == $quartier->id ? 'selected' : '' }}>
                                        {{ $quartier->nom }} - {{ $quartier->village->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Aucun chef enregistré.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $chefs->links() }}
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ChefQuartierModal" tabindex="-1" aria-labelledby="ChefQuartierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ChefQuartierModalLabel">Ajouter un chef de quartier</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('chefs-quartier.store') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="sexe" class="form-label">Sexe</label>
                        <select class="form-select" id="sexe" name="sexe" required>
                            <option value="M">Masculin</option>
                            <option value="F">Feminin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="debut_mandat" class="form-label">Début mandat</label>
                        <input type="date" class="form-control" id="debut_mandat" name="debut_mandat" required>
                    </div>
                    <div class="mb-3">
                        <label for="fin_mandat" class="form-label">Fin mandat</label>
                        <input type="date" class="form-control" id="fin_mandat" name="fin_mandat" required>
                    </div>
                    <div class="mb-3">
                        <label for="quartier_id" class="form-label">Quartier</label>
                        <select class="form-select" id="quartier_id" name="quartier_id" required>
                            @foreach($quartiers as $quartier)
                                <option value="{{ $quartier->id }}">{{ $quartier->nom }} - {{ $quartier->village->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection