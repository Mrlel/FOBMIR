@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Liste des chefs de village</h2>
        <a href="{{ route('chefs-village.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nouveau chef</a>
    </div>

    {{-- Message de succès --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Barre de recherche --}}
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un chef...">
    </div>

    {{-- Tableau des chefs --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle" id="chefsTable">
            <thead class="table-dark">
                <tr>
                    <th>Nom</th>
                    <th>Sexe</th>
                    <th>Village</th>
                    <th>Début mandat</th>
                    <th>Fin mandat</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($chefs as $chef)
                    <tr>
                        <td>{{ $chef->nom }}</td>
                        <td>@if($chef->sexe == 'M') Masculin @else Féminin @endif</td>
                        <td>{{ $chef->village->nom }}</td>
                        <td>{{ $chef->debut_mandat?->format('d/m/Y') }}</td>
                        <td>{{ $chef->fin_mandat?->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('chefs-village.edit', $chef) }}" class="btn btn-sm btn-warning">Modifier</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Aucun chef enregistré.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $chefs->links() }}
    </div>
</div>

{{-- Script recherche JS --}}
<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#chefsTable tbody tr');

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection
