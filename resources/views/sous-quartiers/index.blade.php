@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">

    <!-- ===== HEADER ===== -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h4 fw-bold mb-0">
                <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                Liste des sous-quartiers
            </h1>
            <small class="text-muted">
                Gestion et organisation des sous-quartiers
            </small>
        </div>

        <button class="btn btn-success shadow-sm"
                data-bs-toggle="modal"
                data-bs-target="#SousQuartierModal">
            <i class="bi bi-plus-circle me-1"></i> Ajouter un sous-quartier
        </button>
    </div>

    <!-- ===== RECHERCHE + TOTAL ===== -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 mb-2 mb-md-0">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" id="searchInput"
                       class="form-control"
                       placeholder="Rechercher par nom, quartier ou type...">
            </div>
        </div>

        <div class="col-md-6 text-md-end">
            <span class="badge bg-primary fs-6 px-3 py-2">
                Total : {{ $sousQuartiers->count() }}
            </span>
        </div>
    </div>

    <!-- ===== TABLE CARD ===== -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="sousQuartiersTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Nom</th>
                            <th>Historique</th>
                            <th>Quartier</th>
                            <th>Type</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sousQuartiers as $sousQuartier)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $sousQuartier->nom }}
                                </td>

                                <td class="text-muted">
                                    {{ Str::limit($sousQuartier->historique, 40) }}
                                </td>

                                <td>
                                    <i class="bi bi-building text-secondary me-1"></i>
                                    {{ $sousQuartier->quartier->nom }}
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $sousQuartier->typeSousQuartier?->libelle ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <!-- Modifier -->
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modifSousQuartierModal-{{ $sousQuartier->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <!-- Supprimer -->
                                    <form action="{{ route('sous-quartiers.destroy', $sousQuartier->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer ce sous-quartier ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- ===== MODAL MODIFICATION ===== -->
                            <div class="modal fade"
                                 id="modifSousQuartierModal-{{ $sousQuartier->id }}"
                                 tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content shadow">
                                        <form action="{{ route('sous-quartiers.update', $sousQuartier->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    Modifier le sous-quartier
                                                </h5>
                                                <button type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Nom</label>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="nom"
                                                               value="{{ $sousQuartier->nom }}"
                                                               required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Quartier</label>
                                                        <select class="form-select" name="quartier_id" required>
                                                            @foreach($quartiers as $quartier)
                                                                <option value="{{ $quartier->id }}"
                                                                    {{ $sousQuartier->quartier_id == $quartier->id ? 'selected' : '' }}>
                                                                    {{ $quartier->nom }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Type</label>
                                                        <select class="form-select" name="type_sous_quartier_id" required>
                                                            @foreach($typeSousQuartiers as $typeSousQuartier)
                                                                <option value="{{ $typeSousQuartier->id }}"
                                                                    {{ $sousQuartier->type_sous_quartier_id == $typeSousQuartier->id ? 'selected' : '' }}>
                                                                    {{ $typeSousQuartier->libelle }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label class="form-label">Historique</label>
                                                        <textarea class="form-control" name="historique" rows="3" required>{{ $sousQuartier->historique }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Annuler
                                                </button>
                                                <button class="btn btn-primary">
                                                    Mettre à jour
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-2"></i><br>
                                    Aucun sous-quartier enregistré
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL AJOUT ===== -->
<div class="modal fade" id="SousQuartierModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow">
            <form action="{{ route('sous-quartiers.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un sous-quartier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" name="nom" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Quartier</label>
                            <select class="form-select" name="quartier_id" required>
                                @foreach($quartiers as $quartier)
                                    <option value="{{ $quartier->id }}">{{ $quartier->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select class="form-select" name="type_sous_quartier_id" required>
                                @foreach($typeSousQuartiers as $typeSousQuartier)
                                    <option value="{{ $typeSousQuartier->id }}">{{ $typeSousQuartier->libelle }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Historique</label>
                            <textarea class="form-control" name="historique" rows="3" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== JS RECHERCHE ===== -->
<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
    const filter = this.value.toLowerCase();
    document.querySelectorAll('#sousQuartiersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
});
</script>
@endsection
