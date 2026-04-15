@extends('layouts.admin')

@section('title', 'Chef - ' . $chef->prenom . ' ' . $chef->nom)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-tie mr-2"></i>
                        {{ $chef->prenom }} {{ $chef->nom }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('chefs-sous-quartier.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                        <a href="{{ route('chefs-sous-quartier.edit', $chef) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Informations personnelles
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Prénom :</strong> {{ $chef->prenom }}</p>
                                            <p><strong>Nom :</strong> {{ $chef->nom }}</p>
                                            <p><strong>Téléphone :</strong> {{ $chef->telephone ?? 'Non renseigné' }}</p>
                                            <p><strong>Email :</strong> {{ $chef->email ?? 'Non renseigné' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Date de nomination :</strong> 
                                                @if($chef->date_nomination)
                                                    {{ \Carbon\Carbon::parse($chef->date_nomination)->format('d/m/Y') }}
                                                @else
                                                    Non renseignée
                                                @endif
                                            </p>
                                            <p><strong>Statut :</strong> 
                                                @if($chef->actif)
                                                    <span class="badge badge-success">Actif</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactif</span>
                                                @endif
                                            </p>
                                            <p><strong>Créé le :</strong> {{ $chef->created_at->format('d/m/Y à H:i') }}</p>
                                            <p><strong>Modifié le :</strong> {{ $chef->updated_at->format('d/m/Y à H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($chef->sousQuartier)
                                <div class="card border-left-success mt-3">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-map-marker-alt mr-2"></i>
                                            Zone de responsabilité
                                        </h5>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Sous-quartier :</strong> {{ $chef->sousQuartier->nom }}</p>
                                                @if($chef->sousQuartier->quartier)
                                                    <p><strong>Quartier :</strong> {{ $chef->sousQuartier->quartier->nom }}</p>
                                                    @if($chef->sousQuartier->quartier->village)
                                                        <p><strong>Village :</strong> {{ $chef->sousQuartier->quartier->village->nom }}</p>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Ménages :</strong> {{ $chef->sousQuartier->menages->count() }}</p>
                                                <p><strong>Population déclarée :</strong> {{ $chef->sousQuartier->menages->sum('nb_individus') }}</p>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <a href="{{ route('sous-quartiers.show', $chef->sousQuartier) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> Voir le sous-quartier
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <div class="card border-left-info">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-bolt mr-2"></i>
                                        Actions rapides
                                    </h6>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('chefs-sous-quartier.edit', $chef) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        @if($chef->sousQuartier)
                                            <a href="{{ route('sous-quartiers.show', $chef->sousQuartier) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-map-marker-alt"></i> Voir le sous-quartier
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>
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
                <p>Êtes-vous sûr de vouloir supprimer ce chef de sous-quartier ?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Attention :</strong> Cette action est irréversible.
                </div>
                <p><strong>Chef :</strong> {{ $chef->prenom }} {{ $chef->nom }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('chefs-sous-quartier.destroy', $chef) }}" method="POST" class="d-inline">
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