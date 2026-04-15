@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-map-marked-alt me-2"></i>Liste des quartiers
        </h1>

        <a href="{{ route('quartiers.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus me-1"></i> Ajouter un quartier
        </a>
    </div>

    <!-- INFOS + RECHERCHE -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6 mb-2 mb-md-0">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control"
                       placeholder="Rechercher par nom, village ou type...">
            </div>
        </div>

        <div class="col-md-6 text-md-end text-muted">
            <span class="badge bg-primary fs-6">
                Total : {{ $quartiers->count() }}
            </span>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="quartiersTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Nom</th>
                            <th>Village</th>
                            <th>Type</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quartiers as $quartier)
                        <tr>
                            <td class="fw-semibold">{{ $quartier->nom }}</td>

                            <td>
                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                {{ $quartier->village->nom }}
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $quartier->typeQuartier?->libelle ?? 'N/A' }}
                                </span>
                            </td>

                            <td class="text-end">
                                <!-- Modifier -->
                                <a href="{{ route('quartiers.edit', $quartier->id) }}"
                                   class="btn btn-sm btn-warning me-2"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Supprimer -->
                                <form action="{{ route('quartiers.destroy', $quartier->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer ce quartier ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                Aucun quartier enregistré.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ================= SCRIPT RECHERCHE ================= -->
<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
    const filter = this.value.toLowerCase();
    document.querySelectorAll('#quartiersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
});
</script>
@endsection
