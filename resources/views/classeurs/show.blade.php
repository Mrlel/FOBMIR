@extends('layouts.admin')

@section('title', 'Classeur ' . $classeur->theme . ' - ' . $menage->nom_chef)

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0" style="color: #171e4c; font-weight: 700;">
                <i class="bi bi-folder2-open me-2" style="color: #b68c36;"></i>{{ $classeur->theme }}
            </h2>
            <p class="text-muted small mb-0">Gestion des documents pour le ménage {{ $menage->nom_chef }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('menages.classeurs.index', $menage) }}" class="btn btn-outline-secondary bg-white shadow-sm border-0" style="border-radius: 8px;">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
            @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'superadmin']))
                <a href="{{ route('menages.classeurs.edit', [$menage, $classeur]) }}" class="btn btn-white shadow-sm border-0" style="border-radius: 8px; color: #b68c36;">
                    <i class="bi bi-pencil-square me-1"></i> Modifier
                </a>
                <a href="{{ route('menages.classeurs.documents.create', [$menage, $classeur]) }}" class="btn text-white shadow-sm px-3" style="background-color: #b68c36; border-radius: 8px; border: none;">
                    <i class="bi bi-plus-circle me-1"></i> Nouveau Document
                </a>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="row g-0">
                    <div class="col-md-9 p-4 bg-white">
                        <h6 class="text-uppercase small fw-bold mb-3" style="color: #171e4c; letter-spacing: 1px;">Description du classeur</h6>
                        <p class="text-secondary mb-3" style="font-size: 1.1rem;">
                            {{ $classeur->description ?? 'Aucune description fournie pour ce classeur thématique.' }}
                        </p>
                        <div class="d-flex align-items-center">
                            <span class="badge py-2 px-3" style="background-color: rgba(182, 140, 54, 0.1); color: #b68c36; border-radius: 30px;">
                                <i class="bi bi-calendar3 me-2"></i>Créé le {{ $classeur->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex flex-column justify-content-center align-items-center p-4 text-white" style="background-color: #171e4c;">
                        <div class="display-4 fw-bold" style="color: #b68c36;">{{ $documents->total() }}</div>
                        <div class="text-uppercase small fw-bold opacity-75">Documents</div>
                        <i class="bi bi-files mt-2 opacity-25" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold" style="color: #171e4c;">Liste des pièces justificatives</h5>
                </div>
                <div class="card-body p-0">
                    @if($documents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead style="background-color: #fcf8f0;">
                                    <tr>
                                        <th class="ps-4 border-0 text-muted small text-uppercase">Document</th>
                                        <th class="border-0 text-muted small text-uppercase">Type / Numéro</th>
                                        <th class="border-0 text-muted small text-uppercase">Bénéficiaire</th>
                                        <th class="border-0 text-muted small text-uppercase">Ajouté le</th>
                                        <th class="pe-4 border-0 text-muted small text-uppercase text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documents as $document)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="d-flex justify-content-center align-items-center rounded shadow-sm me-3" 
                                                         style="width: 40px; height: 40px; background-color: rgba(23, 30, 76, 0.05); color: #171e4c;">
                                                        <i class="bi bi-file-earmark-pdf-fill fs-5"></i>
                                                    </div>
                                                    <div>
                                                        <span class="d-block fw-bold" style="color: #171e4c;">{{ $document->libelle }}</span>
                                                        <small class="text-muted">{{ $document->nom_fichier }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge mb-1" style="background-color: #eef0f7; color: #171e4c;">{{ $document->typeDocument->libelle }}</span>
                                                <code class="d-block small text-muted">{{ $document->numero ?? 'SANS NUMÉRO' }}</code>
                                            </td>
                                            <td>
                                                @if($document->individuMenage)
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-circle me-2 text-white d-flex justify-content-center align-items-center fw-bold" 
                                                             style="width:28px; height:28px; font-size:11px; background-color: #b68c36;">
                                                            {{ strtoupper(substr($document->individuMenage->prenom, 0, 1)) }}
                                                        </div>
                                                        <span class="small fw-semibold text-dark">{{ $document->individuMenage->prenom }}</span>
                                                    </div>
                                                @else
                                                    <span class="badge bg-light text-dark border">Global Ménage</span>
                                                @endif
                                            </td>
                                            <td class="small text-secondary">{{ $document->date_ajout->format('d/m/Y') }}</td>
                                            <td class="pe-4 text-end">
                                                <div class="btn-group bg-white border rounded shadow-sm">
                                                    @if(in_array(auth()->user()->role, ['admin', 'superadmin']) && $document->fichier)
                                                        <a href="{{ route('menages.classeurs.documents.download', [$menage, $classeur, $document]) }}" class="btn btn-sm btn-white border-0 py-2 px-3" title="Télécharger">
                                                            <i class="bi bi-download text-primary"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('menages.classeurs.documents.show', [$menage, $classeur, $document]) }}" class="btn btn-sm btn-white border-0 py-2 px-3" title="Voir">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if(in_array(auth()->user()->role, ['point_focal', 'admin', 'superadmin']))
                                                        <a href="{{ route('menages.classeurs.documents.edit', [$menage, $classeur, $document]) }}" class="btn btn-sm btn-white border-0 py-2 px-3">
                                                            <i class="bi bi-pencil text-warning"></i>
                                                        </a>
                                                        <form action="{{ route('menages.classeurs.documents.destroy', [$menage, $classeur, $document]) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce document ?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-white border-0 py-2 px-3">
                                                                <i class="bi bi-trash text-danger"></i>
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
                        <div class="card-footer bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="small text-muted mb-0">Affichage de <strong>{{ $documents->count() }}</strong> document(s) sur {{ $documents->total() }}</p>
                                <div class="pagination-sm">
                                    {{ $documents->links() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-folder-x display-1 opacity-25" style="color: #171e4c;"></i>
                            <h5 class="text-muted mt-3">Aucune pièce jointe</h5>
                            <p class="text-muted small">Ce classeur ne contient aucun document numérisé pour le moment.</p>
                             <a href="{{ route('menages.classeurs.documents.create', [$menage, $classeur]) }}" class="btn text-white px-4" style="background-color: #b68c36; border-radius: 8px;">
                                <i class="bi bi-plus-circle me-1"></i> Ajouter un Document
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Uniformisation des boutons blancs */
    .btn-white { background: #fff; color: #6c757d; }
    .btn-white:hover { background: #f8f9fa; color: #171e4c; }
    
    /* Styles spécifiques pour le survol des lignes */
    .table-hover tbody tr:hover {
        background-color: rgba(182, 140, 54, 0.02);
    }
    
    /* Custom pagination (si possible via CSS) */
    .pagination .page-item.active .page-link {
        background-color: #171e4c;
        border-color: #171e4c;
    }
    .pagination .page-link {
        color: #171e4c;
    }
</style>
@endsection