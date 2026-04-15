@extends('layouts.admin')

@section('title', 'Ménage - ' . $menage->nom_chef)

@section('content')
<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --soft-gray: #f8fafc;
    }

    .card {
        border: none !important;
        border-radius: 12px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
    }

    .detail-label {
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .detail-value {
        color: var(--secondary-blue);
        font-weight: 600;
        font-size: 1.1rem;
    }

    /* Badge Or personnalisé */
    .badge-gold {
        background-color: rgba(182, 140, 54, 0.15);
        color: var(--primary-gold);
    }

    /* Badge Bleu Nuit personnalisé */
    .badge-blue {
        background-color: rgba(23, 30, 76, 0.1);
        color: var(--secondary-blue);
    }

    .table-custom-header {
        background-color: var(--soft-gray);
        color: var(--secondary-blue);
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .action-btn {
        transition: all 0.2s;
        border-radius: 8px;
        font-weight: 600;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .icon-box {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }
</style>

<div class="container-fluid py-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-house-door-fill text-gold me-2" style="color: var(--primary-gold) !important;"></i> {{ $menage->nom_chef }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('menages.index') }}" class="text-decoration-none" style="color: var(--primary-gold);">Ménages</a></li>
                    <li class="breadcrumb-item active">Détails du foyer</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="btn-group shadow-sm">
                <a href="{{ route('menages.pochette.show', $menage) }}" class="btn btn-white border action-btn">
                    <i class="bi bi-folder2-open text-info me-1"></i> Documents
                </a>
                @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'super_admin']))
                <a href="{{ route('menages.edit', $menage) }}" class="btn btn-white border action-btn">
                    <i class="bi bi-pencil-square text-gold me-1" style="color: var(--primary-gold) !important;"></i> Modifier
                </a>
                @endif
                <a href="{{ route('menages.index') }}" class="btn btn-light border action-btn">
                    <i class="bi bi-arrow-left text-muted me-1"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body rounded" style="border: 1px solid var(--secondary-blue);">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box bg-blue me-3" style="background: rgba(23, 30, 76, 0.1);">
                            <i class="bi bi-person-badge-fill" style="color: var(--secondary-blue);"></i>
                        </div>
                        <h5 class="mb-0 fw-bold" style="color: var(--secondary-blue);">Profil du Ménage</h5>
                    </div>
                    
                    <div class="mb-4">
                        <div class="detail-label mb-1">Chef de ménage</div>
                        <div class="detail-value text-uppercase">{{ $menage->nom_chef }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="detail-label mb-1 text-nowrap">Genre du chef</div>
                            <div>
                                @if($menage->sexe_chef)
                                    <span class="badge rounded-pill {{ $menage->sexe_chef == 'M' ? 'badge-blue' : 'badge-gold' }} px-3 py-2">
                                        <i class="bi {{ $menage->sexe_chef == 'M' ? 'bi-gender-male' : 'bi-gender-female' }} me-1"></i>
                                        {{ $menage->sexe_chef == 'M' ? 'Masculin' : 'Féminin' }}
                                    </span>
                                @else
                                    <span class="text-muted small">---</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <div class="detail-label mb-1">Effectif</div>
                            <div class="detail-value text-dark">{{ $menage->nb_individus ?? '0' }} membres</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="detail-label mb-1">Origine / Nationalité</div>
                        <div class="detail-value">
                            <i class="bi bi-globe-europe-africa text-muted me-2"></i>
                            {{ $menage->origine->libelle ?? 'Non définie' }}
                        </div>
                    </div>

                    <hr class="opacity-10">

                    <h6 class="fw-bold mb-3 mt-4" style="color: var(--secondary-blue);">Localisation</h6>
                    @if($menage->sousQuartier)
                        <div class="d-flex align-items-start mb-2">
                            <div class="icon-box bg-gold me-3" style="background: rgba(182, 140, 54, 0.1);">
                                <i class="bi bi-geo-alt-fill" style="color: var(--primary-gold);"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $menage->sousQuartier->nom }}</div>
                                <div class="text-muted small">
                                    {{ $menage->sousQuartier->quartier->nom ?? '' }}<br>
                                    <span class="fw-medium text-secondary">{{ $menage->sousQuartier->quartier->village->nom ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-light border-0 small text-muted">
                            <i class="bi bi-exclamation-circle me-1"></i> Localisation non renseignée
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3 border-0">
                    <h5 class="card-title mb-0 fw-bold" style="color: var(--secondary-blue);">
                        <i class="bi bi-people-fill me-2 text-gold"></i>Membres enregistrés
                    </h5>
                    <span class="badge rounded-pill badge-blue">{{ $menage->individus->count() }} personne(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-4">Nom & Prénoms</th>
                                    <th>Téléphone</th>
                                    <th>Date Naissance</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @forelse($menage->individus as $individu)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3 bg-light rounded-circle d-flex align-items-center justify-content-center fw-bold text-secondary" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                                {{ substr($individu->nom, 0, 1) }}{{ substr($individu->prenom, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark text-uppercase" style="font-size: 0.9rem;">{{ $individu->nom }}</div>
                                                <div class="text-muted small">{{ $individu->prenom }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($individu->telephone)
                                            <span class="small text-dark"><i class="bi bi-phone me-1 text-gold"></i>{{ $individu->telephone }}</span>
                                        @else
                                            <span class="text-muted small">---</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="small"><i class="bi bi-calendar3 me-1 text-muted"></i>{{ \Carbon\Carbon::parse($individu->date_naissance)->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                            <a href="{{ route('individus.show', $individu) }}" class="btn btn-sm btn-white border-end" title="Voir profil">
                                                <i class="bi bi-eye text-primary"></i>
                                            </a>
                                            <a href="{{ route('individus.documents.index', $individu) }}" class="btn btn-sm btn-white" title="Documents">
                                                <i class="bi bi-file-earmark-pdf text-danger"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="bi bi-person-plus display-4 text-muted opacity-25"></i>
                                        <div class="text-muted mb-3 mt-2">Aucun membre n'est encore lié à ce ménage.</div>
                                        <a href="{{ route('individus.create', ['menage_id' => $menage->id]) }}" class="btn btn-sm px-3 text-white" style="background-color: var(--secondary-blue); border-radius: 20px;">
                                            <i class="bi bi-plus-lg me-1"></i> Ajouter un membre
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 text-center">
            <div class="p-3 bg-white d-inline-block rounded-pill shadow-sm border px-4">
                <span class="text-muted small">
                    <i class="bi bi-info-circle me-1 text-gold"></i>
                    Dossier créé le <strong>{{ $menage->created_at->format('d/m/Y') }}</strong> 
                    — Dernière modification le <strong>{{ $menage->updated_at->format('d/m/Y à H:i') }}</strong>
                </span>
            </div>
        </div>
    </div>
</div>
@endsection