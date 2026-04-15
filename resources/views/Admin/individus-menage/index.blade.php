@extends('layouts.admin')

@section('content')
<style>
    /* Intégration de ta charte graphique */
    :root {
        --primary: #f59e0b;
        --primary-dark: #d97706;
        --secondary: #1e3a5f;
        --dark: #0d1b2a;
        --light: #f1f5f9;
    }

    body { background-color: var(--light) !important; color: var(--dark); }

    /* Customisation des composants */
    .page-title { color: var(--secondary); font-weight: 700; }
    
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        background-color: #ffffff;
    }

    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
        font-weight: 600;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
    }

    .btn-secondary { background-color: var(--secondary); border: none; }

    .table thead {
        background-color: var(--secondary);
        color: white;
    }

    .table thead th { border: none; padding: 15px; }

    .badge-id { background-color: rgba(30, 58, 95, 0.1); color: var(--secondary); border: 1px solid var(--secondary); }
    
    .form-label { font-weight: 600; color: var(--secondary); }

    /* Style pour les icônes Bootstrap */
    .bi { vertical-align: -0.125em; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title">
            <i class="bi bi-person-vcard-fill me-2 text-primary"></i> 
            Gestion des Individus Ménages
        </h2>
        <a href="{{ route('admin.individus-menage.create') }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Nouvel Individu
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
            <i class="bi bi-check-all fs-5 me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4 border-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.individus-menage.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small uppercase">Recherche</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Nom, téléphone..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Village</label>
                    <select name="village_id" class="form-select border-1">
                        <option value="">Tous les villages</option>
                        @foreach($villages as $village)
                            <option value="{{ $village->id }}" {{ request('village_id') == $village->id ? 'selected' : '' }}>
                                {{ $village->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Point Focal</label>
                    <select name="point_focal_id" class="form-select">
                        <option value="">Tous les points focaux</option>
                        @foreach($pointsFocaux as $pf)
                            <option value="{{ $pf->id }}" {{ request('point_focal_id') == $pf->id ? 'selected' : '' }}>
                                {{ $pf->prenom }} {{ $pf->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Ménage</label>
                    <select name="menage_id" class="form-select">
                        <option value="">Tous les ménages</option>
                        @foreach($menages as $menage)
                            <option value="{{ $menage->id }}" {{ request('menage_id') == $menage->id ? 'selected' : '' }}>
                                {{ $menage->nom_chef }} ({{ $menage->sousQuartier->nom ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-funnel"></i> Appliquer les filtres
                    </button>
                    <a href="{{ route('admin.individus-menage.index') }}" class="btn btn-light border">
                        <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Nom & Prénom</th>
                            <th>Contact</th>
                            <th>N° Pièce</th>
                            <th>Localisation</th>
                            <th>Ménage / Point Focal</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($individus as $individu)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $individu->nom }} {{ $individu->prenom }}</div>
                                    <div class="text-muted small">
                                        <i class="bi bi-calendar3 me-1"></i> {{ $individu->date_naissance ? \Carbon\Carbon::parse($individu->date_naissance)->format('d/m/Y') : 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <a href="tel:{{ $individu->telephone }}" class="text-decoration-none text-secondary small">
                                        <i class="bi bi-telephone-fill me-1"></i> {{ $individu->telephone ?? 'N/A' }}
                                    </a>
                                </td>
                                <td><span class="badge badge-id">{{ $individu->numpiece }}</span></td>
                                <td>
                                    <div class="small"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $individu->menage->sousQuartier->quartier->village->nom ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="small"><strong>Chef:</strong> {{ $individu->menage->nom_chef ?? 'N/A' }}</div>
                                    <div class="text-muted extra-small">PF: {{ $individu->pointFocal->prenom ?? '' }} {{ $individu->pointFocal->nom ?? 'N/A' }}</div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group shadow-sm">
                                        <a href="{{ route('admin.individus-menage.show', $individu) }}" class="btn btn-white btn-sm border" title="Voir">
                                            <i class="bi bi-eye-fill text-info"></i>
                                        </a>
                                        <a href="{{ route('admin.individus-menage.edit', $individu) }}" class="btn btn-white btn-sm border" title="Modifier">
                                            <i class="bi bi-pencil-square text-warning"></i>
                                        </a>
                                        <form action="{{ route('admin.individus-menage.destroy', $individu) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-white btn-sm border" title="Supprimer">
                                                <i class="bi bi-trash3-fill text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-cloud-slash display-4 text-muted"></i>
                                    <p class="mt-3 text-muted">Aucun résultat trouvé pour votre recherche.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $individus->links() }}
        </div>
    </div>
</div>
@endsection