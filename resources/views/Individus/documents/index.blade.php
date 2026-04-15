@extends('layouts.admin')

@section('title', 'Documents de ' . $individu->prenom . ' ' . $individu->nom)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt mr-2"></i>
                        Documents de {{ $individu->prenom }} {{ $individu->nom }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('individus.show', $individu) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour à l'individu
                        </a>
                        <a href="{{ route('individus.documents.create', $individu) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nouveau document
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Informations de l'individu -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5><i class="fas fa-user mr-2"></i>Informations de l'individu</h5>
                                        <p class="mb-1"><strong>Nom complet :</strong> {{ $individu->prenom }} {{ $individu->nom }}</p>
                                        @if($individu->telephone)
                                            <p class="mb-1"><strong>Téléphone :</strong> {{ $individu->telephone }}</p>
                                        @endif
                                        @if($individu->menage)
                                            <p class="mb-0"><strong>Ménage :</strong> {{ $individu->menage->nom_chef }}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-chart-bar mr-2"></i>Statistiques</h6>
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <h4 class="text-primary">{{ $documents->count() }}</h4>
                                                <small class="text-muted">Documents</small>
                                            </div>
                                            <div class="col-4">
                                                <h4 class="text-success">{{ $documents->where('fichier', '!=', null)->count() }}</h4>
                                                <small class="text-muted">Avec fichier</small>
                                            </div>
                                            <div class="col-4">
                                                <h4 class="text-info">{{ $documents->groupBy('type')->count() }}</h4>
                                                <small class="text-muted">Types</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($documents->count() > 0)
                        <!-- Filtres -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filterType">Filtrer par type :</label>
                                    <select class="form-control" id="filterType">
                                        <option value="">Tous les types</option>
                                        @foreach($documents->pluck('type')->unique()->filter() as $type)
                                            <option value="{{ $type }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="searchDocument">Rechercher :</label>
                                    <input type="text" class="form-control" id="searchDocument" placeholder="Nom du document...">
                                </div>
                            </div>
                        </div>

                        <!-- Liste des documents -->
                        <div class="row" id="documentsContainer">
                            @foreach($documents as $document)
                                <div class="col-md-6 col-lg-4 mb-3 document-item" 
                                     data-type="{{ $document->type }}" 
                                     data-name="{{ strtolower($document->nom) }}">
                                    <div class="card h-100 border-left-primary">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0">{{ Str::limit($document->nom, 30) }}</h6>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                            type="button" 
                                                            data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('individus.documents.show', [$individu, $document]) }}">
                                                                <i class="fas fa-eye mr-2"></i>Voir
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('individus.documents.edit', [$individu, $document]) }}">
                                                                <i class="fas fa-edit mr-2"></i>Modifier
                                                            </a>
                                                        </li>
                                                        @if($document->fichier)
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('individus.documents.download', [$individu, $document]) }}">
                                                                    <i class="fas fa-download mr-2"></i>Télécharger
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" 
                                                               onclick="confirmDelete({{ $document->id }}, '{{ $document->nom }}')">
                                                                <i class="fas fa-trash mr-2"></i>Supprimer
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            @if($document->type)
                                                <span class="badge badge-info mb-2">{{ $document->type }}</span>
                                            @endif

                                            @if($document->numero_document)
                                                <p class="card-text small mb-1">
                                                    <strong>N° :</strong> {{ $document->numero_document }}
                                                </p>
                                            @endif

                                            @if($document->date_emission)
                                                <p class="card-text small mb-1">
                                                    <strong>Émis le :</strong> {{ \Carbon\Carbon::parse($document->date_emission)->format('d/m/Y') }}
                                                </p>
                                            @endif

                                            @if($document->description)
                                                <p class="card-text small text-muted">
                                                    {{ Str::limit($document->description, 80) }}
                                                </p>
                                            @endif

                                            <!-- Statut du fichier -->
                                            <div class="mt-2">
                                                @if($document->fichier)
                                                    <div class="d-flex align-items-center text-success">
                                                        @php
                                                            $extension = pathinfo($document->fichier, PATHINFO_EXTENSION);
                                                        @endphp
                                                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                                            <i class="fas fa-image mr-1"></i>
                                                        @elseif(strtolower($extension) === 'pdf')
                                                            <i class="fas fa-file-pdf mr-1"></i>
                                                        @elseif(in_array(strtolower($extension), ['doc', 'docx']))
                                                            <i class="fas fa-file-word mr-1"></i>
                                                        @elseif(in_array(strtolower($extension), ['xls', 'xlsx']))
                                                            <i class="fas fa-file-excel mr-1"></i>
                                                        @else
                                                            <i class="fas fa-file mr-1"></i>
                                                        @endif
                                                        <small>Fichier attaché</small>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center text-warning">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                                        <small>Aucun fichier</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="card-footer bg-transparent">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    {{ $document->created_at->format('d/m/Y') }}
                                                </small>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('individus.documents.show', [$individu, $document]) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($document->fichier)
                                                        <a href="{{ route('individus.documents.download', [$individu, $document]) }}" 
                                                           class="btn btn-outline-success btn-sm">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Message si aucun résultat après filtrage -->
                        <div id="noResults" class="text-center py-4" style="display: none;">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun document trouvé</h5>
                            <p class="text-muted">Essayez de modifier vos critères de recherche.</p>
                        </div>
                    @else
                        <!-- Aucun document -->
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun document</h5>
                            <p class="text-muted">Cet individu n'a encore aucun document enregistré.</p>
                            <a href="{{ route('individus.documents.create', $individu) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Ajouter le premier document
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce document ?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Attention :</strong> Cette action est irréversible. Le fichier attaché sera également supprimé.
                </div>
                <p><strong>Document :</strong> <span id="documentName"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Filtrage par type
    $('#filterType').on('change', function() {
        filterDocuments();
    });

    // Recherche par nom
    $('#searchDocument').on('keyup', function() {
        filterDocuments();
    });

    function filterDocuments() {
        const typeFilter = $('#filterType').val().toLowerCase();
        const searchFilter = $('#searchDocument').val().toLowerCase();
        let visibleCount = 0;

        $('.document-item').each(function() {
            const documentType = $(this).data('type') ? $(this).data('type').toLowerCase() : '';
            const documentName = $(this).data('name');
            
            const typeMatch = !typeFilter || documentType === typeFilter;
            const nameMatch = !searchFilter || documentName.includes(searchFilter);
            
            if (typeMatch && nameMatch) {
                $(this).show();
                visibleCount++;
            } else {
                $(this).hide();
            }
        });

        // Afficher/masquer le message "aucun résultat"
        if (visibleCount === 0 && ($('#filterType').val() || $('#searchDocument').val())) {
            $('#noResults').show();
        } else {
            $('#noResults').hide();
        }
    }
});

function confirmDelete(documentId, documentName) {
    $('#documentName').text(documentName);
    $('#deleteForm').attr('action', '{{ route("individus.documents.destroy", [$individu, ":id"]) }}'.replace(':id', documentId));
    $('#deleteModal').modal('show');
}
</script>
@endpush
@endsection