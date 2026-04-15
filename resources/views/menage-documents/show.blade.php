@extends('layouts.admin')

@section('title', 'Document - ' . $document->libelle)

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --soft-gold: rgba(182, 140, 54, 0.1);
    }

    /* Fil d'Ariane */
    .breadcrumb-item a { color: var(--primary-gold); text-decoration: none; font-weight: 500; }
    .breadcrumb-item.active { color: var(--secondary-blue); }

    /* Cards */
    .card { border-radius: 12px; border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
    .card-header { background-color: #fff; border-bottom: 1px solid #edf2f7; padding: 1.25rem; border-radius: 12px 12px 0 0 !important; }
    
    /* Document Info */
    .info-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #718096; font-weight: 700; margin-bottom: 2px; }
    .info-value { color: var(--secondary-blue); font-weight: 600; font-size: 1rem; }

    /* File Section */
    .file-box {
        background-color: #f8fafc;
        border: 2px dashed #e2e8f0;
        border-radius: 10px;
        padding: 20px;
        transition: all 0.3s ease;
    }
    .file-icon-lg { font-size: 3rem; margin-right: 1.5rem; }

    /* Buttons Styles */
    .btn-gold { background-color: var(--primary-gold); color: white; border: none; }
    .btn-gold:hover { background-color: #a37d2f; color: white; }
    .btn-blue { background-color: var(--secondary-blue); color: white; border: none; }
    .btn-blue:hover { background-color: #0f153a; color: white; }
</style>

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('menages.show', $menage) }}">{{ $menage->nom_chef }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('menages.classeurs.show', [$menage, $classeur]) }}">{{ $classeur->theme }}</a></li>
                    <li class="breadcrumb-item active">{{ $document->libelle }}</li>
                </ol>
            </nav>
            <h2 class="h3 fw-bold text-dark mb-0">
                <i class="bi bi-file-earmark-text text-gold me-2"></i>Détails du document
            </h2>
        </div>
        <div class="col-auto">
            <a href="{{ route('menages.classeurs.show', [$menage, $classeur]) }}" class="btn btn-outline-secondary px-3 shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4 border-left-gold bg-light">
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="info-label">Libellé du document</div>
                            <div class="info-value">{{ $document->libelle }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-label">Type de document</div>
                            <div class="info-value">
                                <span class="badge bg-light text-dark border">{{ $document->typeDocument->libelle ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-label">Numéro de référence</div>
                            <div class="info-value text-muted">{{ $document->numero ?? 'Non renseigné' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-label">Membre concerné</div>
                            <div class="info-value">
                                @if($document->individu)
                                    <i class="bi bi-person me-1"></i>{{ $document->individu->nom }} {{ $document->individu->prenom }}
                                @else
                                    <span class="text-muted">Ménage complet</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr class="text-light">

                    @if($document->fichier)

            
                        <div class="file-box d-flex align-items-center mb-3">
                            <div class="file-icon-lg">
                                @php $ext = strtolower(pathinfo($document->fichier, PATHINFO_EXTENSION)); @endphp
                                @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                    <i class="bi bi-file-earmark-image text-success"></i>
                                @elseif($ext === 'pdf')
                                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                                @else
                                    <i class="bi bi-file-earmark-text text-primary"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark fw-bold">Fichier attaché</h6>
                                <p class="small text-muted mb-0">
                                    {{ basename($document->fichier) }} 
                                    ({{ strtoupper($ext) }})
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('menages.classeurs.documents.download', [$menage, $classeur, $document]) }}" class="btn btn-gold shadow-sm">
                                    <i class="bi bi-download me-1"></i> Télécharger
                                </a>
                            </div>
                        </div>
                    

                        @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                            <div class="mt-4 text-center p-3 bg-light rounded shadow-sm">
                                <p class="text-start info-label mb-3"><i class="bi bi-eye me-1"></i>Aperçu rapide</p>
                                <img src="{{ asset('storage/' . $document->fichier) }}" 
                                     class="img-fluid rounded shadow" 
                                     style="max-height: 500px; border: 4px solid #fff;">
                            </div>
                        @endif
                    @else
                        <div class="alert alert-light border d-flex align-items-center p-4">
                            <i class="bi bi-exclamation-triangle fs-3 text-warning me-3"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">Aucun fichier numérisé</h6>
                                <p class="small mb-0">Veuillez modifier le document pour attacher une version numérique.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold text-dark">
                    <i class="bi bi-lightning-charge text-gold me-2"></i>Actions
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('menages.classeurs.documents.edit', [$menage, $classeur, $document]) }}" class="btn btn-blue py-2">
                            <i class="bi bi-pencil-square me-2"></i> Modifier les infos
                        </a>
                        <button type="button" class="btn btn-outline-danger py-2" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i> Supprimer le document
                        </button>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header fw-bold text-dark">
                    <i class="bi bi-info-circle text-gold me-2"></i>Contexte
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 py-3">
                            <span class="text-muted"><i class="bi bi-house me-2"></i>Chef de ménage</span>
                            <span class="fw-bold text-dark">{{ $menage->nom_chef }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 py-3">
                            <span class="text-muted"><i class="bi bi-folder2-open me-2"></i>Classeur</span>
                            <span class="badge bg-soft-gold text-warning">{{ $classeur->theme }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 py-3">
                            <span class="text-muted"><i class="bi bi-calendar-check me-2"></i>Ajouté le</span>
                            <span class="text-dark small">{{ $document->created_at->format('d/m/Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-dark">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-exclamation-octagon text-danger display-1 mb-3"></i>
                <h5 class="mb-2">Êtes-vous certain ?</h5>
                <p class="text-muted mb-0">Cette action supprimera définitivement le document <br><strong>"{{ $document->libelle }}"</strong> ainsi que son fichier.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('menages.classeurs.documents.destroy', [$menage, $classeur, $document]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">Confirmer la suppression</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection