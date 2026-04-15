@extends('layouts.admin')

@section('title', 'Dossiers individuels - ' . $menage->nom_chef)

@section('content')
<div class="container-fluid py-4 rounded" style="background-color: #f8f9fa; min-height: 100vh;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold" style="color: #171e4c;">
                <i class="bi bi-folder2-open me-2" style="color: #b68c36;"></i>
                Gestion des dossiers individuels
            </h1>
            <p class="text-muted small mb-0">Organisation des documents par membre du ménage</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ url()->previous() }}" class="btn btn-white shadow-sm border-0" style="border-radius: 8px;">
                <i class="bi bi-arrow-left"></i> Retour
            </a>

            @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'superadmin']))
                <a href="{{ route('menages.dossiers.create', $menage) }}" class="btn text-white shadow-sm px-3" style="background-color: #b68c36; border-radius: 8px; border: none;">
                    <i class="bi bi-plus-lg me-1"></i> Nouveau dossier
                </a>
            @endif
        </div>
    </div>

    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 12px; background-color: #171e4c;">
                <div class="d-flex justify-content-between align-items-center text-white">
                    <div>
                        <small class="text-uppercase opacity-75 fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Membres</small>
                        <h3 class="mb-0 fw-bold">{{ $individus->count() }}</h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: rgba(182, 140, 54, 0.2); color: #b68c36;">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 12px; background-color: #FFFFFF;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Dossiers actifs</small>
                        <h3 class="mb-0 fw-bold" style="color: #171e4c;">{{ $dossiers->count() }}</h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: rgba(23, 30, 76, 0.05); color: #171e4c;">
                        <i class="bi bi-folder-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 12px; background-color: #FFFFFF;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Sans dossier</small>
                        <h3 class="mb-0 fw-bold" style="color: #b68c36;">{{ $individus->count() - $dossiers->count() }}</h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: rgba(182, 140, 54, 0.1); color: #b68c36;">
                        <i class="bi bi-person-x fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($individus->count() > $dossiers->count())
    <div class="mb-5 p-4" style="background-color: #e5e4e2ff; border-radius: 7px;">
        <h5 class="mb-3 fw-bold" style="color: #171e4c;">
            <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>
            Membres en attente de dossier
        </h5>

        <div class="row g-3">
            @foreach($individus as $individu)
                @if(!$dossiers->where('individu_menage_id', $individu->id)->count())
                    <div class="col-md-3 col-sm-6">
                        <div class="card border-0 shadow-sm text-center p-3 h-100" style="border-radius: 12px;">
                            <div class="mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #eef0f7; color: #171e4c;">
                                <i class="bi bi-person-circle fs-3"></i>
                            </div>
                            <div class="fw-bold small text-dark mb-2">{{ $individu->prenom }}</div>
                            @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'superadmin']))
                                <a href="{{ route('menages.dossiers.create', $menage) }}?individu={{ $individu->id }}"
                                   class="btn btn-sm text-white" style="background-color: #171e4c; border-radius: 6px;">
                                    <i class="bi bi-plus"></i> Créer
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    <h5 class="mb-4 fw-bold" style="color: #171e4c;">Dossiers constitués</h5>
    <div class="row">
    @forelse($dossiers as $dossier)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; transition: transform 0.2s;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-3">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center text-white shadow-sm" 
                             style="width: 48px; height: 48px; background-color: #b68c36; flex-shrink: 0;">
                            <i class="bi bi-person-vcard fs-4"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h5 class="mb-0 fw-bold text-truncate" style="color: #171e4c;">{{ $dossier->nom }}</h5>
                            <p class="text-muted small mb-0">
                                {{ $dossier->individuMenage->prenom }} {{ $dossier->individuMenage->nom }}
                            </p>
                        </div>
                    </div>

                    <p class="text-secondary small mb-4" style="line-height: 1.5;">
                        {{ $dossier->description ? Str::limit($dossier->description, 90) : 'Aucune description disponible.' }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background-color: #f8f9fa;">
                        <span class="badge py-2 px-3" style="background-color: rgba(23, 30, 76, 0.08); color: #171e4c; border-radius: 8px;">
                            <i class="bi bi-collection me-1"></i>
                            {{ $dossier->classeurs_count }} classeurs
                        </span>
                        <small class="text-muted" style="font-size: 0.75rem;">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $dossier->created_at->format('d/m/Y') }}
                        </small>
                    </div>
                </div>

                <div class="card-footer bg-white border-top-0 p-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('menages.dossiers.show', [$menage, $dossier]) }}"
                       class="btn btn-sm px-3 shadow-sm border" style="border-radius: 8px; color: #171e4c; background: #fff;">
                        <i class="bi bi-eye-fill me-1"></i> Voir détails
                    </a>

                    @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'superadmin']))
                    <div class="d-flex gap-1">
                        <a href="{{ route('menages.dossiers.classeurs.create', [$menage, $dossier]) }}"
                           class="btn btn-sm text-white" style="background-color: #b68c36; border-radius: 8px;" title="Ajouter un classeur">
                            <i class="bi bi-plus-circle"></i>
                        </a>
                        <a href="{{ route('menages.dossiers.edit', [$menage, $dossier]) }}"
                           class="btn btn-sm btn-white border shadow-sm" style="border-radius: 8px; color: #171e4c;" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="mb-3 opacity-25" style="color: #171e4c;">
                <i class="bi bi-folder-x" style="font-size: 5rem;"></i>
            </div>
            <h5 class="text-muted fw-bold">Aucun dossier trouvé</h5>
            <p class="small text-muted">Commencez par créer un dossier pour l'un des membres du ménage.</p>
        </div>
    @endforelse
    </div>
</div>

<style>
    .btn-white { background: #fff; color: #6c757d; }
    .btn-white:hover { background: #f8f9fa; color: #171e4c; }
    .card:hover { transform: translateY(-5px); transition: 0.3s ease-in-out; }
</style>
@endsection