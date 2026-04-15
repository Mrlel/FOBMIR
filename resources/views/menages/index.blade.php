@extends('layouts.admin')

@section('content')
@include('layouts.message')

<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(182, 140, 54, 0.08);
        transition: 0.3s;
    }

    .search-focus:focus {
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 0.25rem rgba(182, 140, 54, 0.15);
    }

    .table-custom-header {
        background-color: var(--secondary-blue);
        color: white;
    }

    /* Badges personnalisés aux couleurs du projet */
    .badge-masculin { background-color: rgba(23, 30, 76, 0.1); color: var(--secondary-blue); }
    .badge-feminin { background-color: rgba(182, 140, 54, 0.1); color: var(--primary-gold); }

    .btn-action {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
        border: none;
        background: transparent;
    }
</style>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-house-door-fill text-gold me-2" style="color: var(--primary-gold) !important;"></i> Gestion des Ménages
            </h1>
            <p class="text-muted small mb-0">Visualisez et gérez les informations des foyers enregistrés.</p>
        </div>
        <a href="{{ route('menages.create') }}" class="btn px-4 py-2 shadow-sm fw-bold text-white" style="background-color: var(--primary-gold);">
            <i class="bi bi-plus-lg me-2"></i>Ajouter un ménage
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="searchMenage" class="form-control border-start-0 ps-0 search-focus" 
                               placeholder="Rechercher par nom du chef, quartier ou origine...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="menagesTable">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4 py-3 border-0">Chef de ménage</th>
                        <th class="border-0 text-center">Sexe</th>
                        <th class="border-0">Localisation</th>
                        <th class="border-0">Origine</th>
                        <th class="text-center pe-4 border-0">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($menages as $menage)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3 rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                     style="width: 38px; height: 38px; background: rgba(23, 30, 76, 0.05); color: var(--secondary-blue);">
                                    {{ substr($menage->nom_chef, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $menage->nom_chef }}</div>
                                    <div class="text-muted small">
                                        <i class="bi bi-people me-1"></i>{{ $menage->nb_individus ?? 0 }} membre(s)
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($menage->sexe_chef == 'M')
                                <span class="badge badge-masculin px-3 py-2 rounded-pill">
                                    <i class="bi bi-gender-male me-1"></i> Masculin
                                </span>
                            @else
                                <span class="badge badge-feminin px-3 py-2 rounded-pill">
                                    <i class="bi bi-gender-female me-1"></i> Féminin
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center small">
                                <i class="bi bi-geo-alt-fill text-gold me-2" style="color: var(--primary-gold) !important;"></i>
                                <span class="text-dark">{{ $menage->sousQuartier?->nom ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-secondary border fw-normal py-2 px-3">
                                <i class="bi bi-globe2 me-1"></i> {{ $menage->origine?->libelle ?? 'Non définie' }}
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('menages.pochette.show', $menage->id) }}" class="btn-action text-info" title="Pochette numérique">
                                    <i class="bi bi-folder-symlink-fill fs-5"></i>
                                </a>
                                <a href="{{ route('menages.show', $menage->id) }}" class="btn-action text-secondary" title="Détails">
                                    <i class="bi bi-eye-fill fs-5"></i>
                                </a>
                                <a href="{{ route('menages.edit', $menage->id) }}" class="btn-action" style="color: var(--primary-gold);" title="Modifier">
                                    <i class="bi bi-pencil-square fs-5"></i>
                                </a>
                                <form action="{{ route('menages.destroy', $menage->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action text-danger" onclick="return confirm('Supprimer ce ménage ?');" title="Supprimer">
                                        <i class="bi bi-trash3-fill fs-5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-house-x display-4 text-muted opacity-25"></i>
                            <h6 class="text-muted fw-bold mt-3">Aucun ménage enregistré</h6>
                            <p class="text-muted small">Les foyers créés apparaîtront ici.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 px-2">
        <small class="text-muted fst-italic">Affichage de {{ $menages->count() }} résultats sur {{ $menages->total() }}</small>
        <div class="shadow-sm rounded">
            {{ $menages->links() }}
        </div>
    </div>
</div>

<script>
document.getElementById('searchMenage').addEventListener('input', function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#menagesTable tbody tr:not(#noResultMenage)');
    let visibleCount = 0;

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const isMatch = text.includes(filter);
        row.style.display = isMatch ? '' : 'none';
        if (isMatch) visibleCount++;
    });

    let noRes = document.getElementById('noResultMenage');
    if (visibleCount === 0 && rows.length > 0) {
        if (!noRes) {
            noRes = document.createElement('tr');
            noRes.id = 'noResultMenage';
            noRes.innerHTML = `<td colspan="5" class="text-center py-4 text-muted small fst-italic">Aucune correspondance trouvée pour "${this.value}"</td>`;
            document.querySelector('#menagesTable tbody').appendChild(noRes);
        }
    } else if (noRes) {
        noRes.remove();
    }
});
</script>
@endsection