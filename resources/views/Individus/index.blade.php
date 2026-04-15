@extends('layouts.admin')

@section('content')
<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(182, 140, 54, 0.08);
        transition: 0.3s;
    }
    
    .badge-id { font-size: 0.75rem; font-weight: 600; }
    
    .search-focus:focus {
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 0.25rem rgba(182, 140, 54, 0.15);
    }

    .bg-blue-night {
        background-color: var(--secondary-blue) !important;
    }

    .text-gold {
        color: var(--primary-gold) !important;
    }

    .avatar-initial {
        background-color: rgba(23, 30, 76, 0.1);
        color: var(--secondary-blue);
        border: 1px solid rgba(23, 30, 76, 0.2);
    }

    /* Style spécifique pour les headers de table aux couleurs du projet */
    .table-custom-header {
        background-color: var(--secondary-blue);
        color: white;
    }
</style>

<div class="container-fluid py-4">

    @include('layouts.message')

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-people-fill text-gold me-2"></i>Liste des Individus
            </h1>
            <p class="text-muted small mb-0">Gestion et suivi des membres des ménages enregistrés.</p>
        </div>
        <a href="{{ route('individus.create') }}" class="btn px-4 py-2 shadow-sm fw-bold text-white" style="background-color: var(--primary-gold);">
            <i class="bi bi-person-plus-fill me-2"></i>Nouvel Individu
        </a>
    </div>

    @if(Auth::user()->role == 'point_focal')
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-md-4 p-4 border-end bg-light">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-3 me-3" style="background: rgba(23, 30, 76, 0.1);">
                            <i class="bi bi-person-badge text-blue-night fs-4" style="color: var(--secondary-blue) !important;"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Point focal</div>
                            <div class="fw-bold text-dark">{{ $user->nom }} {{ $user->prenom }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 p-4 border-end">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-3 me-3" style="background: rgba(182, 140, 54, 0.1);">
                            <i class="bi bi-geo-alt-fill text-gold fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Circonscription</div>
                            <div class="fw-bold text-dark">{{ $user->village->nom ?? 'Non défini' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="bi bi-server text-success fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Total Enregistré</div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $individus->total() }} <small class="text-muted fs-6">individus</small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-7 col-lg-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0 ps-0 search-focus" 
                               placeholder="Rechercher par nom, téléphone, chef de ménage...">
                    </div>
                </div>
                <div class="col-md-5 col-lg-4 text-md-end">
                    <div class="btn-group w-100 w-md-auto">
                        <button type="button" class="btn btn-outline-dark dropdown-toggle fw-bold shadow-sm" data-bs-toggle="dropdown">
                            <i class="bi bi-cloud-download me-2"></i>Exporter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item py-2" href="#"><i class="bi bi-filetype-pdf text-danger me-2"></i>Format PDF</a></li>
                            <li><a class="dropdown-item py-2" href="#"><i class="bi bi-filetype-xls text-success me-2"></i>Format Excel</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="individusTable">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4 py-3 border-0">Réf.</th>
                        <th class="border-0">Identité</th>
                        <th class="border-0">Contact</th>
                        <th class="border-0">Date de Naissance</th>
                        <th class="border-0">Ménage associé</th>
                        <th class="border-0">Profession</th>
                        <th class="text-end pe-4 border-0">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($individus as $individu)
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-light text-dark border badge-id">#{{ $loop->iteration }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="fw-bold text-dark">{{ strtoupper($individu->nom) }}</div>
                                    <div class="text-muted small">{{ $individu->prenom }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($individu->telephone)
                                <a href="tel:{{ $individu->telephone }}" class="text-decoration-none text-muted small">
                                    <i class="bi bi-phone-fill me-1 text-gold"></i>{{ $individu->telephone }}
                                </a>
                            @else
                                <span class="text-muted small">---</span>
                            @endif
                        </td>
                        <td>
                            <span class="small text-dark">
                                <i class="bi bi-calendar-check me-1 text-gold"></i>
                                {{ \Carbon\Carbon::parse($individu->date_naissance)->format('d M Y') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-light text-dark border fw-normal py-2 px-3">
                                <i class="bi bi-house-door-fill me-1" style="color: var(--secondary-blue);"></i>
                                {{ $individu->menage->nom_chef ?? 'Non rattaché' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted small fw-medium">{{ $individu->emploi ?? 'Sans emploi' }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('individus.show', $individu->id) }}" class="btn btn-sm btn-outline-info border-0" title="Détails">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('individus.edit', $individu->id) }}" class="btn btn-sm btn-outline-warning border-0" title="Modifier">
                                    <i class="bi bi-pencil-square" style="color: var(--primary-gold);"></i>
                                </a>
                                <form action="{{ route('individus.destroy', $individu->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression de cet individu ?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger border-0" title="Supprimer">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-folder-x display-4 text-muted opacity-25"></i>
                            <h6 class="text-muted fw-bold mt-3">Aucun individu trouvé</h6>
                            <p class="text-muted small">Commencez par ajouter un membre à un ménage.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($individus->hasPages())
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body d-flex justify-content-center py-2">
            {{ $individus->links() }}
        </div>
    </div>
    @endif
</div>
@endsection