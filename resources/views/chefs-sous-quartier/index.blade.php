@extends('layouts.admin')

@section('title', 'Chefs de sous-quartier')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-tie mr-2"></i>
                        Chefs de sous-quartier
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('chefs-sous-quartier.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nouveau chef
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($chefs->count() > 0)
                        <!-- Filtres -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="searchChef" placeholder="Rechercher un chef...">
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" id="filterSousQuartier">
                                    <option value="">Tous les sous-quartiers</option>
                                    @foreach($chefs->pluck('sousQuartier')->unique()->filter() as $sousQuartier)
                                        <option value="{{ $sousQuartier->id }}">{{ $sousQuartier->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Liste des chefs -->
                        <div class="table-responsive">
                            <table class="table table-striped" id="chefsTable">
                                <thead>
                                    <tr>
                                        <th>Nom complet</th>
                                        <th>Téléphone</th>
                                        <th>Sous-quartier</th>
                                        <th>Quartier</th>
                                        <th>Date de nomination</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($chefs as $chef)
                                        <tr data-sous-quartier="{{ $chef->sous_quartier_id }}" 
                                            data-name="{{ strtolower($chef->nom . ' ' . $chef->prenom) }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-2">
                                                        {{ strtoupper(substr($chef->prenom, 0, 1) . substr($chef->nom, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $chef->prenom }} {{ $chef->nom }}</strong>
                                                        @if($chef->email)
                                                            <br><small class="text-muted">{{ $chef->email }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $chef->telephone ?? '-' }}</td>
                                            <td>
                                                @if($chef->sousQuartier)
                                                    <a href="{{ route('sous-quartiers.show', $chef->sousQuartier) }}" class="text-decoration-none">
                                                        {{ $chef->sousQuartier->nom }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Non assigné</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($chef->sousQuartier && $chef->sousQuartier->quartier)
                                                    {{ $chef->sousQuartier->quartier->nom }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($chef->date_nomination)
                                                    {{ \Carbon\Carbon::parse($chef->date_nomination)->format('d/m/Y') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($chef->actif)
                                                    <span class="badge badge-success">Actif</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('chefs-sous-quartier.show', $chef) }}" 
                                                       class="btn btn-outline-primary" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('chefs-sous-quartier.edit', $chef) }}" 
                                                       class="btn btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="confirmDelete({{ $chef->id }}, '{{ $chef->prenom }} {{ $chef->nom }}')" 
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if(method_exists($chefs, 'links'))
                            <div class="d-flex justify-content-center">
                                {{ $chefs->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Aucun chef -->
                        <div class="text-center py-5">
                            <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun chef de sous-quartier</h5>
                            <p class="text-muted">Commencez par ajouter le premier chef de sous-quartier.</p>
                            <a href="{{ route('chefs-sous-quartier.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Ajouter le premier chef
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce chef de sous-quartier ?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Attention :</strong> Cette action est irréversible.
                </div>
                <p><strong>Chef :</strong> <span id="chefName"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Recherche
    $('#searchChef').on('keyup', function() {
        filterChefs();
    });

    // Filtre par sous-quartier
    $('#filterSousQuartier').on('change', function() {
        filterChefs();
    });

    function filterChefs() {
        const searchTerm = $('#searchChef').val().toLowerCase();
        const sousQuartierFilter = $('#filterSousQuartier').val();
        
        $('#chefsTable tbody tr').each(function() {
            const name = $(this).data('name');
            const sousQuartier = $(this).data('sous-quartier');
            
            const nameMatch = !searchTerm || name.includes(searchTerm);
            const sousQuartierMatch = !sousQuartierFilter || sousQuartier == sousQuartierFilter;
            
            if (nameMatch && sousQuartierMatch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
});

function confirmDelete(chefId, chefName) {
    $('#chefName').text(chefName);
    $('#deleteForm').attr('action', '{{ route("chefs-sous-quartier.destroy", ":id") }}'.replace(':id', chefId));
    $('#deleteModal').modal('show');
}
</script>

<style>
.avatar-sm {
    width: 35px;
    height: 35px;
    font-size: 12px;
}
</style>
@endpush
@endsection