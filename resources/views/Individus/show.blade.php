@extends('layouts.admin')

@section('content')
<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --light-gold: rgba(182, 140, 54, 0.1);
    }

    .bg-gradient-legal {
        background: linear-gradient(135deg, var(--secondary-blue) 0%, #2a357d 100%);
    }

    /* Style des cartes d'information */
    .info-card {
        background: white;
        border: none;
        border-radius: 7px;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .info-card-header {
        background: var(--light-gold);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(182, 140, 54, 0.2);
    }

    .info-card-header h5 {
        color: var(--secondary-blue);
        font-weight: 700;
        margin-bottom: 0;
        font-size: 1.1rem;
    }

    .info-item {
        padding: 0.75rem 1.5rem;
        border-bottom: 1px solid #f8f9fa;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .info-item:last-child { border-bottom: none; }

    .info-label {
        color: #6c757d;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .info-value {
        color: var(--secondary-blue);
        font-weight: 500;
    }

    /* Fil d'Ariane de localisation */
    .location-breadcrumb {
        background: #fdfaf3;
        border: 1px solid var(--light-gold);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 2rem;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "→";
        color: var(--primary-gold);
    }

    .btn-gold {
        background: var(--primary-gold);
        color: white;
        border: none;
    }
    .btn-gold:hover { background: #a37b2f; color: white; }
</style>

<div class="p-4">
    @include('layouts.message')

    <header class="card bg-gradient-legal text-white mb-4 border-0 ">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="bi bi-person-badge-fill me-2 text-gold" style="color: var(--primary-gold);"></i>
                        {{ $individu->prenom }} {{ $individu->nom }}
                    </h2>
                    <p class="mb-0 opacity-75">ID Individu: #IND-{{ $individu->id }} | Enregistré le {{ $individu->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('individus.index') }}" class="btn btn-light  px-3">
                        <i class="bi bi-arrow-left me-1"></i> Retour
                    </a>
                    <a href="{{ route('individus.edit', $individu->id) }}" class="btn btn-gold  px-4">
                        <i class="bi bi-pencil-square me-1"></i> Modifier
                    </a>
                </div>
            </div>
        </div>
    </header>

    @if($geolocalisation)
    <div class="location-breadcrumb">
        <h6 class="text-gold fw-bold mb-3"><i class="bi bi-geo-alt-fill me-2"></i>Localisation Administrative</h6>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">{{ $geolocalisation['pays'] ?? 'N/A' }}</li>
                <li class="breadcrumb-item text-dark">{{ $geolocalisation['district'] ?? 'N/A' }}</li>
                <li class="breadcrumb-item text-dark">{{ $geolocalisation['region'] ?? 'N/A' }}</li>
                <li class="breadcrumb-item text-dark">{{ $geolocalisation['departement'] ?? 'N/A' }}</li>
                <li class="breadcrumb-item text-dark">{{ $geolocalisation['sous_prefecture'] ?? 'N/A' }}</li>
                <li class="breadcrumb-item active fw-bold text-gold">{{ $geolocalisation['village'] ?? 'N/A' }}</li>
            </ol>
        </nav>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="info-card">
                <div class="info-card-header">
                    <h5><i class="bi bi-person-vcard me-2"></i>État Civil & Identité</h5>
                </div>
                <div class="card-body p-0">
                    <div class="info-item">
                        <span class="info-label">Nom complet</span>
                        <span class="info-value text-uppercase fw-bold">{{ $individu->nom }} {{ $individu->prenom }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date de naissance</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($individu->date_naissance)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Lieu de naissance</span>
                        <span class="info-value">{{ $individu->lieu_naissance }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Téléphone</span>
                        <span class="info-value">{{ $individu->telephone ?? 'Non renseigné' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Profession / Emploi</span>
                        <span class="info-value"><span class="badge bg-light text-dark border">{{ $individu->emploi ?? 'Aucun' }}</span></span>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-card-header">
                    <h5><i class="bi bi-file-earmark-text me-2"></i>Pièces Justificatives</h5>
                </div>
                <div class="card-body p-0">
                    <div class="info-item">
                        <span class="info-label">N° de Pièce (CNI/Passport)</span>
                        <span class="info-value fw-bold">{{ $individu->numpiece }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">N° Extrait de naissance</span>
                        <span class="info-value">{{ $individu->num_extrait_naissance }}</span>
                    </div>
                    @if($individu->doc_piece)
                    <div class="p-3 bg-light text-center">
                        <a href="{{ asset('storage/' . $individu->doc_piece) }}" target="_blank" class="btn btn-outline-primary btn-sm ">
                            <i class="bi bi-cloud-download me-2"></i>Consulter la pièce numérisée
                        </a>
                        @if($individu->nom_piece)
                            <small class="d-block mt-2 text-muted">{{ $individu->nom_piece }}</small>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="info-card border-start border-gold border-4">
                <div class="info-card-header">
                    <h5><i class="bi bi-house-door me-2"></i>Appartenance au Ménage</h5>
                </div>
                <div class="card-body p-0">
                    <div class="info-item">
                        <span class="info-label">Chef de ménage</span>
                        <span class="info-value fw-bold text-primary">{{ $individu->menage->nom_chef ?? 'Non défini' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Sexe du Chef</span>
                        <span class="info-value">{{ $individu->menage->sexe_chef == 'M' ? 'Masculin' : 'Féminin' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Localisation</span>
                        <span class="info-value">
                            {{ $individu->menage->sousQuartier->nom ?? 'N/A' }} 
                            <small class="text-muted">({{ $individu->menage->sousQuartier->quartier->nom ?? 'N/A' }})</small>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Taille du ménage</span>
                        <span class="info-value badge bg-secondary ">{{ $individu->menage->nb_individus }} membres</span>
                    </div>
                    @if($individu->menage->origine)
                    <div class="info-item">
                        <span class="info-label">Origine géographique</span>
                        <span class="info-value">{{ $individu->menage->origine->libelle }}</span>
                    </div>
                    @endif
                </div>
            </div>

            @if($individu->pointFocal)
            <div class="info-card">
                <div class="info-card-header">
                    <h5><i class="bi bi-shield-check me-2"></i>Agent de Recensement</h5>
                </div>
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-person-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $individu->pointFocal->nom }} {{ $individu->pointFocal->prenom }}</h6>
                        <small class="text-muted"><i class="bi bi-telephone me-1"></i> {{ $individu->pointFocal->telephone }}</small>
                    </div>
                </div>
            </div>
            @endif

            <div class="card bg-light border-0" style="border-radius: 15px;">
                <div class="card-body py-2 px-4">
                    <div class="d-flex justify-content-between small text-muted">
                        <span><i class="bi bi-clock-history me-1"></i> Créé le : {{ $individu->created_at->format('d/m/Y H:i') }}</span>
                        <span><i class="bi bi-pencil me-1"></i> MàJ le : {{ $individu->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection