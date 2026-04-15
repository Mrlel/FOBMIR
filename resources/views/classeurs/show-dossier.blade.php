@extends('layouts.admin')

@section('title', 'Classeur ' . $classeur->theme . ' - ' . $menage->nom_chef)

@section('content')

<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --light-bg: #f8fafc;
    }

    /* Header & Titles */
    .header-box {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 4px;
    }

    .page-title {
        color: var(--secondary-blue);
        font-weight: 800;
    }

    /* Cards */
    .card-clean {
        border-radius: 4px;
        border : 1px solid #171e4c;
        overflow: hidden;
    }

    /* Stats Box inside card */
    .stat-box {
        background-color: var(--secondary-blue);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        min-height: 120px;
    }

    .stat-box h2 {
        font-weight: 800;
        margin-bottom: 0;
        color: var(--primary-gold);
    }

    /* Table styling */
    .table thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: var(--secondary-blue);
        border-top: none;
        padding: 1rem;
    }

    .table tbody td {
        padding: 1rem;
        color: #475569;
    }

    /* File Icon */
    .file-icon {
        width: 40px;
        height: 40px;
        background-color: rgba(23, 30, 76, 0.05);
        color: var(--secondary-blue);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    /* Badge Custom */
    .badge-gold-soft {
        background-color: rgba(182, 140, 54, 0.1);
        color: var(--primary-gold);
        font-weight: 600;
    }

    /* Avatar Mini */
    .avatar-mini {
        width: 28px;
        height: 28px;
        background-color: var(--primary-gold);
        color: white;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: bold;
    }

    /* Action Buttons */
    .btn-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
        background: white;
        text-decoration: none !important;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }

    .btn-gold {
        background-color: var(--primary-gold);
        color: white !important;
        border: none;
        font-weight: 600;
    }
</style>

<div class="container-fluid py-4">

    <div class="header-box d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="mb-2 mb-md-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="{{ route('menages.index') }}" class="text-decoration-none text-muted">Ménages</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('menages.show', $menage) }}" class="text-decoration-none text-muted">{{ $menage->nom_chef }}</a></li>
                    <li class="breadcrumb-item active text-primary-gold">Classeur</li>
                </ol>
            </nav>
            <h2 class="page-title h3 mb-0">
                <i class="bi bi-collection-fill me-2" style="color: var(--primary-gold)"></i>
                {{ $classeur->theme }}
            </h2>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary border-0 btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>

            @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'superadmin']))
                <a href="{{ route('menages.classeurs.documents.create', [$menage, $classeur]) }}"
                   class="btn btn-gold btn-sm px-3 shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Nouveau document
                </a>
            @endif
        </div>
    </div>

    <div class="card card-clean mb-4 bg-white">
        <div class="row g-0">
            <div class="col-md-9 p-4 border-end">
                <div class="d-flex align-items-center mb-3">
                    <h6 class="text-uppercase small fw-bold text-muted mb-0" style="letter-spacing: 1px;">
                        Description du contenu
                    </h6>
                    <div class="ms-3 flex-grow-1 border-top opacity-10"></div>
                </div>
                <p class="text-dark mb-3 leading-relaxed">
                    {{ $classeur->description ?? 'Aucune description détaillée fournie pour ce classeur.' }}
                </p>

                <div class="d-flex align-items-center text-muted small">
                    <i class="bi bi-calendar-check me-2 text-primary-gold"></i>
                    Ouvert le {{ $classeur->created_at->format('d/m/Y à H:i') }}
                </div>
            </div>

            <div class="col-md-3 stat-box">
                <div class="opacity-75 small text-uppercase fw-bold mb-1">Documents</div>
                <h2>{{ $documents->total() }}</h2>
                <i class="bi bi-file-earmark-text fs-4 mt-2 opacity-25"></i>
            </div>
        </div>
    </div>

    <div class="card card-clean bg-white shadow-sm">
        <div class="card-body p-0">
            @if($documents->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Libellé du document</th>
                                <th>Référence / Type</th>
                                <th>Bénéficiaire</th>
                                <th>Date d'ajout</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($documents as $document)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="file-icon me-3">
                                            <i class="bi bi-file-earmark-richtext"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $document->libelle }}</div>
                                            <div class="small text-muted" style="font-size: 0.75rem;">
                                                <i class="bi bi-paperclip me-1"></i>{{ $document->nom_fichier }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge badge-gold-soft mb-1 d-block w-fit-content">
                                        {{ $document->typeDocument->libelle }}
                                    </span>
                                    <span class="text-muted small">#{{ $document->numero ?? 'N/A' }}</span>
                                </td>

                                <td>
                                    @if($document->individuMenage)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-mini me-2">
                                                {{ strtoupper(substr($document->individuMenage->prenom, 0, 1)) }}
                                            </div>
                                            <span class="small fw-semibold text-dark">
                                                {{ $document->individuMenage->prenom }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="badge bg-light text-muted fw-normal border">
                                            Ménage Global
                                        </span>
                                    @endif
                                </td>

                                <td class="small text-muted">
                                    {{ $document->date_ajout->format('d/m/Y') }}
                                </td>

                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-2">
                                        @if($document->fichier)
                                            <a href="{{ route('menages.classeurs.documents.download', [$menage, $classeur, $document]) }}"
                                               class="btn-action text-primary" title="Télécharger">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        @endif

                                        <a href="{{ route('menages.classeurs.documents.show', [$menage, $classeur, $document]) }}"
                                           class="btn-action text-dark" title="Consulter">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'superadmin']))
                                            <a href="{{ route('menages.classeurs.documents.edit', [$menage, $classeur, $document]) }}"
                                               class="btn-action text-warning" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <form action="{{ route('menages.classeurs.documents.destroy', [$menage, $classeur, $document]) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Supprimer définitivement ce document ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action text-danger border-danger-subtle" title="Supprimer">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white border-top py-3 d-flex flex-wrap justify-content-between align-items-center">
                    <p class="text-muted small mb-0">
                        Affichage de <strong>{{ $documents->count() }}</strong> document(s) sur un total de <strong>{{ $documents->total() }}</strong>
                    </p>
                    <div class="mt-2 mt-md-0">
                        {{ $documents->links() }}
                    </div>
                </div>

            @else
                <div class="text-center py-5">
                    <div class="mb-3 opacity-25">
                        <i class="bi bi-file-earmark-plus" style="font-size: 4rem; color: var(--secondary-blue);"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Classeur vide</h5>
                    <p class="text-muted small mx-auto" style="max-width: 300px;">
                        Aucun document n'a été numérisé dans ce classeur thématique.
                    </p>

                    @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'superadmin']))
                        <a href="{{ route('menages.classeurs.documents.create', [$menage, $classeur]) }}"
                           class="btn btn-gold btn-sm px-4 mt-2">
                            <i class="bi bi-upload me-2"></i>Charger un document
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@endsection