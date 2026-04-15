@extends('layouts.admin')

@section('title', 'Document - ' . $document->nom)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt mr-2"></i>
                        {{ $document->nom }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('individus.documents.index', $individu) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour aux documents
                        </a>
                        <a href="{{ route('individus.documents.edit', [$individu, $document]) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        @if($document->fichier)
                            <a href="{{ route('individus.documents.download', [$individu, $document]) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-download"></i> Télécharger
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <!-- Fil d'Ariane -->
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('individus.index') }}">Individus</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('individus.show', $individu) }}">{{ $individu->prenom }} {{ $individu->nom }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('individus.documents.index', $individu) }}">Documents</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $document->nom }}</li>
                        </ol>
                    </nav>

                    <div class="row">
                        <div class="col-md-8">
                            <!-- Informations du document -->
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Informations du document
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Nom :</strong> {{ $document->nom }}</p>
                                            <p><strong>Type :</strong> 
                                                <span class="badge badge-info">{{ $document->type ?? 'Non spécifié' }}</span>
                                            </p>
                                            @if($document->numero_document)
                                                <p><strong>Numéro :</strong> {{ $document->numero_document }}</p>
                                            @endif
                                            <p><strong>Propriétaire :</strong> {{ $individu->prenom }} {{ $individu->nom }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Créé le :</strong> {{ $document->created_at->format('d/m/Y à H:i') }}</p>
                                            <p><strong>Modifié le :</strong> {{ $document->updated_at->format('d/m/Y à H:i') }}</p>
                                            @if($document->date_emission)
                                                <p><strong>Date d'émission :</strong> {{ \Carbon\Carbon::parse($document->date_emission)->format('d/m/Y') }}</p>
                                            @endif
                                            @if($document->date_expiration)
                                                <p><strong>Date d'expiration :</strong> 
                                                    <span class="@if(\Carbon\Carbon::parse($document->date_expiration)->isPast()) text-danger @elseif(\Carbon\Carbon::parse($document->date_expiration)->diffInDays() < 30) text-warning @else text-success @endif">
                                                        {{ \Carbon\Carbon::parse($document->date_expiration)->format('d/m/Y') }}
                                                        @if(\Carbon\Carbon::parse($document->date_expiration)->isPast())
                                                            <i class="fas fa-exclamation-triangle ml-1" title="Expiré"></i>
                                                        @elseif(\Carbon\Carbon::parse($document->date_expiration)->diffInDays() < 30)
                                                            <i class="fas fa-clock ml-1" title="Expire bientôt"></i>
                                                        @endif
                                                    </span>
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    @if($document->description)
                                        <div class="mt-3">
                                            <strong>Description :</strong>
                                            <p class="mt-2">{{ $document->description }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Fichier attaché -->
                            @if($document->fichier)
                                <div class="card border-left-success mt-3">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-paperclip mr-2"></i>
                                            Fichier attaché
                                        </h5>
                                        
                                        <div class="d-flex align-items-center">
                                            <div class="file-icon mr-3">
                                                @php
                                                    $extension = pathinfo($document->fichier, PATHINFO_EXTENSION);
                                                @endphp
                                                @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                                    <i class="fas fa-image fa-2x text-success"></i>
                                                @elseif(strtolower($extension) === 'pdf')
                                                    <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                                @elseif(in_array(strtolower($extension), ['doc', 'docx']))
                                                    <i class="fas fa-file-word fa-2x text-primary"></i>
                                                @elseif(in_array(strtolower($extension), ['xls', 'xlsx']))
                                                    <i class="fas fa-file-excel fa-2x text-success"></i>
                                                @else
                                                    <i class="fas fa-file fa-2x text-secondary"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-1"><strong>Nom du fichier :</strong> {{ basename($document->fichier) }}</p>
                                                <p class="mb-1"><strong>Extension :</strong> {{ strtoupper($extension) }}</p>
                                                @if(file_exists(storage_path('app/public/' . $document->fichier)))
                                                    <p class="mb-0"><strong>Taille :</strong> {{ number_format(filesize(storage_path('app/public/' . $document->fichier)) / 1024, 2) }} KB</p>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('individus.documents.download', [$individu, $document]) }}" 
                                                   class="btn btn-success">
                                                    <i class="fas fa-download"></i> Télécharger
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Aperçu pour les images -->
                                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']) && file_exists(storage_path('app/public/' . $document->fichier)))
                                            <div class="mt-3">
                                                <strong>Aperçu :</strong>
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . $document->fichier) }}" 
                                                         alt="{{ $document->nom }}" 
                                                         class="img-fluid" 
                                                         style="max-height: 300px; border: 1px solid #ddd; border-radius: 4px;">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="card border-left-warning mt-3">
                                    <div class="card-body text-center">
                                        <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                                        <h5 class="text-warning">Aucun fichier attaché</h5>
                                        <p class="text-muted">Ce document n'a pas de fichier attaché.</p>
                                        <a href="{{ route('individus.documents.edit', [$individu, $document]) }}" 
                                           class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Modifier pour ajouter un fichier
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <!-- Actions rapides -->
                            <div class="card border-left-info">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-bolt mr-2"></i>
                                        Actions rapides
                                    </h6>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('individus.documents.edit', [$individu, $document]) }}" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        @if($document->fichier)
                                            <a href="{{ route('individus.documents.download', [$individu, $document]) }}" 
                                               class="btn btn-success btn-sm">
                                                <i class="fas fa-download"></i> Télécharger
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations sur l'individu -->
                            <div class="card border-left-secondary mt-3">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-user mr-2"></i>
                                        Propriétaire
                                    </h6>
                                    <ul class="list-unstyled small">
                                        <li><strong>Nom :</strong> {{ $individu->prenom }} {{ $individu->nom }}</li>
                                        @if($individu->telephone)
                                            <li><strong>Téléphone :</strong> {{ $individu->telephone }}</li>
                                        @endif
                                        @if($individu->menage)
                                            <li><strong>Ménage :</strong> {{ $individu->menage->nom_chef }}</li>
                                        @endif
                                        <li><strong>Documents :</strong> {{ $individu->documents->count() }}</li>
                                    </ul>
                                    <a href="{{ route('individus.show', $individu) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Voir l'individu
                                    </a>
                                </div>
                            </div>

                            <!-- Statut d'expiration -->
                            @if($document->date_expiration)
                                <div class="card border-left-{{ \Carbon\Carbon::parse($document->date_expiration)->isPast() ? 'danger' : (\Carbon\Carbon::parse($document->date_expiration)->diffInDays() < 30 ? 'warning' : 'success') }} mt-3">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-calendar-alt mr-2"></i>
                                            Statut d'expiration
                                        </h6>
                                        @if(\Carbon\Carbon::parse($document->date_expiration)->isPast())
                                            <div class="text-danger">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                <strong>Expiré depuis {{ \Carbon\Carbon::parse($document->date_expiration)->diffForHumans() }}</strong>
                                            </div>
                                        @elseif(\Carbon\Carbon::parse($document->date_expiration)->diffInDays() < 30)
                                            <div class="text-warning">
                                                <i class="fas fa-clock mr-1"></i>
                                                <strong>Expire {{ \Carbon\Carbon::parse($document->date_expiration)->diffForHumans() }}</strong>
                                            </div>
                                        @else
                                            <div class="text-success">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                <strong>Valide jusqu'au {{ \Carbon\Carbon::parse($document->date_expiration)->format('d/m/Y') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
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
                <p><strong>Document :</strong> {{ $document->nom }}</p>
                <p><strong>Propriétaire :</strong> {{ $individu->prenom }} {{ $individu->nom }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('individus.documents.destroy', [$individu, $document]) }}" method="POST" class="d-inline">
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
@endsection