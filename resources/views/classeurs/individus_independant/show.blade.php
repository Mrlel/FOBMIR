@extends('layouts.individu')

@section('title', 'Détails du classeur')

@section('content')

<style>
    :root {
        --teal: #1B5E57;
        --teal-dark: #0E3D38;
        --orange: #F06A1D;
        --orange-light: #FF8C42;
        --card-border: #e8e2da;
    }

    /* En-tête stylisé */
    .classeur-header {
        background: white;
        border-radius: 16px;
        border: 1.5px solid var(--card-border);
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    /* Fil d'Ariane KindFlow */
    .breadcrumb-item a { color: var(--teal); font-weight: 600; }
    .breadcrumb-item.active { color: var(--orange); font-weight: 700; }

    /* Badge Type Document */
    .badge-type {
        background: rgba(27, 94, 87, 0.08);
        color: var(--teal);
        font-weight: 700;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 5px 10px;
        border-radius: 6px;
    }

    /* Style du tableau */
    .document-table thead {
        background: var(--teal-dark);
        color: white;
    }
    .document-table th {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1px;
        padding: 15px;
        border: none;
    }

    .btn-action-primary {
        background-color: var(--teal);
        color: white;
        border-radius: 10px;
        font-weight: 600;
        transition: 0.3s;
        border: none;
    }
    .btn-action-primary:hover {
        background-color: var(--teal-dark);
        color: white;
        transform: translateY(-2px);
    }

    .btn-download {
        background-color: var(--orange);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        transition: 0.3s;
    }
    .btn-download:hover {
        background-color: var(--orange-light);
        color: white;
    }

    .doc-icon {
        width: 44px;
        height: 44px;
        background: #FBF7F2;
        color: var(--orange);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.3rem;
        border: 1px solid rgba(240, 106, 29, 0.1);
    }

    /* Style Modal Paiement */
    .modal-content { border-radius: 20px; border: none; overflow: hidden; }
    .modal-header { background: var(--teal-dark); color: white; border: none; }
    .modal-header .btn-close { filter: invert(1); }
    .payment-label { font-weight: 700; font-size: 0.85rem; color: var(--teal-dark); margin-bottom: 5px; }
</style>

<div class="container-fluid py-3">
    <div class="classeur-header p-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item small"><a href="{{ route('mes.classeurs') }}" class="text-decoration-none">Mes classeurs</a></li>
                    <li class="breadcrumb-item small active" aria-current="page">{{ $classeur->theme }}</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-1" style="font-family: 'Playfair Display', serif;">
                <i class="bi bi-folder2-open text-orange me-2"></i>{{ $classeur->theme }}
            </h3>
            <p class="text-muted small mb-0">
                <span class="badge bg-light text-dark border">{{ $documents->count() }} document(s)</span> au total dans ce dossier.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('individu.classeurs.documents.create', $classeur) }}" class="btn btn-action-primary px-3 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Nouveau document
            </a>
            <a href="{{ route('mes.classeurs') }}" class="btn btn-light border fw-bold px-3 text-muted">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-4 border-orange">
        <div class="card-body p-4">
            <h6 class="fw-bold text-teal-dark mb-2 small text-uppercase" style="letter-spacing: 1px;">Consignes d'archivage</h6>
            <p class="text-secondary mb-0">
                {{ $classeur->description ?? 'Ce dossier regroupe vos pièces administratives. Veillez à ce que les copies soient bien lisibles.' }}
            </p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">   
        <div class="card-body p-0">
            @if($documents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 document-table">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4">Document & Type</th>
                                <th>Référence / N°</th>
                                <th>Date d'ajout</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="doc-icon me-3">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-0">{{ $document->libelle }}</div>
                                                <span class="badge badge-type">{{ $document->typeDocument->libelle ?? 'Document' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="bg-light px-2 py-1 rounded text-teal fw-bold small">{{ $document->numero ?? 'NON RENSEIGNÉ' }}</code>
                                    </td>
                                    <td>
                                        <div class="small text-muted">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            {{ optional($document->date_ajout)->format('d M Y') ?? $document->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button type="button" class="btn btn-sm btn-download px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $document->id }}">
                                            <i class="bi bi-shield-lock me-1"></i> Payer & Télécharger
                                        </button>

                                        <div class="modal fade" id="paymentModal{{ $document->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold"><i class="bi bi-credit-card me-2"></i>Accès au document</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4 text-start">
                                                        <div class="text-center mb-4">
                                                            <div class="doc-icon mx-auto mb-3" style="width:60px; height:60px; font-size: 2rem;">
                                                                <i class="bi bi-file-pdf"></i>
                                                            </div>
                                                            <h6 class="fw-bold">{{ $document->libelle }}</h6>
                                                            <p class="small text-muted">Veuillez renseigner vos coordonnées pour le reçu de paiement.</p>
                                                        </div>

                                                        <form action="{{ route('payment.initiate', $document->id) }}" method="POST">
                                                            @csrf
                                                            <div class="row g-2">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="payment-label">Nom</label>
                                                                    <input type="text" name="nom" class="form-control" value="{{ auth('individu')->user()->nom }}" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="payment-label">Prénom</label>
                                                                    <input type="text" name="prenom" class="form-control" value="{{ auth('individu')->user()->prenom }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="payment-label">Email de réception</label>
                                                                <input type="email" name="email" class="form-control" value="{{ auth('individu')->user()->email }}" required>
                                                            </div>
                                                            <div class="mb-4">
                                                                <label class="payment-label">N° de téléphone Mobile Money</label>
                                                                <input type="tel" name="telephone" class="form-control" placeholder="Ex: 0707070707" required>
                                                                <div class="mt-2 d-flex gap-2 justify-content-center opacity-75">
                                                                    <img src="https://tse2.mm.bing.net/th/id/OIP._Rd2v34V_DsugF_ByEdaxwHaEX?rs=1&pid=ImgDetMain&o=7&rm=3" width="20" alt="Orange">
                                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/9/93/New-mtn-logo.jpg" width="20" alt="MTN">
                                                                    <small class="text-muted">Orange, MTN, Moov, Wave acceptés</small>
                                                                </div>
                                                            </div>

                                                            <button type="submit" class="btn btn-action-primary w-100 py-3 shadow">
                                                                <i class="bi bi-lock-fill me-2"></i> Initier le paiement sécurisé
                                                            </button>
                                                            
                                                            <p class="text-center text-muted mt-3 mb-0" style="font-size: 0.7rem;">
                                                                <i class="bi bi-shield-fill-check text-success"></i> Transaction sécurisée par KindFlow & FedaPay
                                                            </p>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">  
                    <div class="mb-3 opacity-25">
                        <i class="bi bi-folder-x" style="font-size: 4rem; color: var(--teal);"></i>
                    </div>
                    <h6 class="text-muted fw-bold">Ce classeur ne contient aucun document</h6>
                    <p class="small text-muted mb-4">Commencez à numériser vos documents pour les sécuriser ici.</p>
                    <a href="{{ route('individu.classeurs.documents.create', $classeur) }}" class="btn btn-action-primary">
                        <i class="bi bi-plus-lg me-1"></i> Ajouter mon premier fichier
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection