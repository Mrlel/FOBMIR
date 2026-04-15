@extends('layouts.individu')

@section('content')

<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
    }

    /* En-tête stylisé */
    .classeur-header {
        background: white;
        border-radius: 9px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    /* Badge Type Document */
    .badge-type {
        background: rgba(23, 30, 76, 0.08);
        color: var(--secondary-blue);
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
    }

    /* Style du tableau / Liste */
    .document-table thead {
        background: #f8f9fa;
    }
    .document-table th {
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #adb5bd;
        font-weight: 700;
        letter-spacing: 0.5px;
        border: none;
    }

    .btn-download {
        background-color: var(--secondary-blue);
        color: white;
        border-radius: 8px;
        transition: 0.3s;
        font-weight: 500;
    }
    .btn-download:hover {
        background-color: var(--primary-gold);
        color: white;
    }

    .doc-icon {
        width: 40px;
        height: 40px;
        background: #fdfaf3;
        color: var(--primary-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 1.2rem;
    }
</style>

<div class="container-fluid py-3">
    <div class="classeur-header p-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item small"><a href="{{ route('mes.classeurs') }}" class="text-decoration-none text-muted">Mes classeurs</a></li>
                    <li class="breadcrumb-item small active text-primary-gold" aria-current="page">{{ $dossier->nom }}</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-folder2-open text-primary-gold me-2"></i>{{ $classeur->theme }}
            </h3>
            <p class="text-muted small mb-0">
                {{ $documents->count() }} document(s) archivé(s) dans cette section.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('individu.classeurs.documents.create', $classeur) }}" class="btn px-3 text-white fw-bold shadow-sm" style="background: var(--secondary-blue)">
                <i class="bi bi-plus-lg me-1"></i> Nouveau document
            </a>
            <a href="{{ route('mes.classeurs') }}" class="btn btn-light border fw-bold px-3 text-muted">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold text-dark mb-2 small text-uppercase" style="letter-spacing: 1px;">Description</h6>
            <p class="text-secondary mb-0">
                {{ $classeur->description ?? 'Aucune consigne ou description particulière pour ce classeur.' }}
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
                                <th class="ps-4">Document</th>
                                <th>Référence / N°</th>
                                <th>Date d'ajout</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="doc-icon me-3">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-0">{{ $document->libelle }}</div>
                                                <span class="badge badge-type">{{ $document->typeDocument->libelle ?? 'Document' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="text-muted small fw-bold">{{ $document->numero ?? 'N/A' }}</code>
                                    </td>
                                    <td>
                                        <div class="small text-muted">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            {{ optional($document->date_ajout)->format('d M Y') ?? $document->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a class="btn btn-sm btn-download px-3" href="{{ route('individu.classeurs.documents.download', [$classeur, $document]) }}" title="Paiement requis avant téléchargement">
                                            <i class="bi bi-credit-card me-1"></i> Payer & Télécharger
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">  
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="60" class="mb-3 opacity-25" alt="Empty">
                    <h6 class="text-muted fw-bold">Ce classeur est vide</h6>
                    <p class="small text-muted">Ajoutez votre premier document pour commencer l'archivage.</p>
                    <a href="{{ route('individu.classeurs.documents.create', $classeur) }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-plus-lg me-1"></i> Ajouter un fichier
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection