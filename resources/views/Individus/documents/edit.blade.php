@extends('layouts.admin')

@section('title', 'Modifier le document - ' . $document->nom)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier le document
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('individus.documents.show', [$individu, $document]) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour au document
                        </a>
                    </div>
                </div>

                <form action="{{ route('individus.documents.update', [$individu, $document]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Informations du contexte -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h5><i class="fas fa-info-circle mr-2"></i>Informations du document</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Propriétaire :</strong> {{ $individu->prenom }} {{ $individu->nom }}</p>
                                            @if($individu->menage)
                                                <p class="mb-1"><strong>Ménage :</strong> {{ $individu->menage->nom_chef }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Créé le :</strong> {{ $document->created_at->format('d/m/Y à H:i') }}</p>
                                            <p class="mb-0"><strong>Dernière modification :</strong> {{ $document->updated_at->format('d/m/Y à H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <!-- Nom du document -->
                                <div class="form-group">
                                    <label for="nom">Nom du document <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" 
                                           name="nom" 
                                           value="{{ old('nom', $document->nom) }}" 
                                           required
                                           placeholder="Entrez le nom du document">
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Type de document -->
                                <div class="form-group">
                                    <label for="type">Type de document</label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type">
                                        <option value="">Sélectionner un type</option>
                                        <option value="Carte d'identité" {{ old('type', $document->type) == 'Carte d\'identité' ? 'selected' : '' }}>Carte d'identité</option>
                                        <option value="Passeport" {{ old('type', $document->type) == 'Passeport' ? 'selected' : '' }}>Passeport</option>
                                        <option value="Acte de naissance" {{ old('type', $document->type) == 'Acte de naissance' ? 'selected' : '' }}>Acte de naissance</option>
                                        <option value="Certificat de mariage" {{ old('type', $document->type) == 'Certificat de mariage' ? 'selected' : '' }}>Certificat de mariage</option>
                                        <option value="Diplôme" {{ old('type', $document->type) == 'Diplôme' ? 'selected' : '' }}>Diplôme</option>
                                        <option value="Certificat médical" {{ old('type', $document->type) == 'Certificat médical' ? 'selected' : '' }}>Certificat médical</option>
                                        <option value="Contrat de travail" {{ old('type', $document->type) == 'Contrat de travail' ? 'selected' : '' }}>Contrat de travail</option>
                                        <option value="Attestation" {{ old('type', $document->type) == 'Attestation' ? 'selected' : '' }}>Attestation</option>
                                        <option value="Certificat de scolarité" {{ old('type', $document->type) == 'Certificat de scolarité' ? 'selected' : '' }}>Certificat de scolarité</option>
                                        <option value="Permis de conduire" {{ old('type', $document->type) == 'Permis de conduire' ? 'selected' : '' }}>Permis de conduire</option>
                                        <option value="Autre" {{ old('type', $document->type) == 'Autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Numéro du document -->
                                <div class="form-group">
                                    <label for="numero_document">Numéro du document</label>
                                    <input type="text" 
                                           class="form-control @error('numero_document') is-invalid @enderror" 
                                           id="numero_document" 
                                           name="numero_document" 
                                           value="{{ old('numero_document', $document->numero_document) }}"
                                           placeholder="Numéro ou référence du document">
                                    @error('numero_document')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date d'émission -->
                                <div class="form-group">
                                    <label for="date_emission">Date d'émission</label>
                                    <input type="date" 
                                           class="form-control @error('date_emission') is-invalid @enderror" 
                                           id="date_emission" 
                                           name="date_emission" 
                                           value="{{ old('date_emission', $document->date_emission ? \Carbon\Carbon::parse($document->date_emission)->format('Y-m-d') : '') }}">
                                    @error('date_emission')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date d'expiration -->
                                <div class="form-group">
                                    <label for="date_expiration">Date d'expiration</label>
                                    <input type="date" 
                                           class="form-control @error('date_expiration') is-invalid @enderror" 
                                           id="date_expiration" 
                                           name="date_expiration" 
                                           value="{{ old('date_expiration', $document->date_expiration ? \Carbon\Carbon::parse($document->date_expiration)->format('Y-m-d') : '') }}">
                                    @error('date_expiration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Laissez vide si le document n'expire pas</small>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4"
                                              placeholder="Description détaillée du document (optionnel)">{{ old('description', $document->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Fichier actuel -->
                                @if($document->fichier)
                                    <div class="form-group">
                                        <label>Fichier actuel</label>
                                        <div class="alert alert-info">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
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
                                                    <p class="mb-1"><strong>{{ basename($document->fichier) }}</strong></p>
                                                    <p class="mb-0 small text-muted">Téléchargé le {{ $document->created_at->format('d/m/Y à H:i') }}</p>
                                                </div>
                                                <div>
                                                    <a href="{{ route('individus.documents.download', [$individu, $document]) }}" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-download"></i> Télécharger
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Nouveau fichier -->
                                <div class="form-group">
                                    <label for="fichier">{{ $document->fichier ? 'Remplacer le fichier' : 'Ajouter un fichier' }}</label>
                                    <div class="custom-file">
                                        <input type="file" 
                                               class="custom-file-input @error('fichier') is-invalid @enderror" 
                                               id="fichier" 
                                               name="fichier"
                                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.xls,.xlsx">
                                        <label class="custom-file-label" for="fichier">Choisir un fichier...</label>
                                    </div>
                                    @error('fichier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ $document->fichier ? 'Laissez vide pour conserver le fichier actuel. ' : '' }}
                                        Formats acceptés : PDF, DOC, DOCX, JPG, JPEG, PNG, GIF, XLS, XLSX. Taille max : 10MB
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Informations -->
                                <div class="card border-left-info">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            Informations
                                        </h6>
                                        <ul class="list-unstyled small">
                                            <li><strong>Propriétaire :</strong> {{ $individu->prenom }} {{ $individu->nom }}</li>
                                            <li><strong>Créé le :</strong> {{ $document->created_at->format('d/m/Y à H:i') }}</li>
                                            <li><strong>Modifié le :</strong> {{ $document->updated_at->format('d/m/Y à H:i') }}</li>
                                            @if($document->fichier && file_exists(storage_path('app/public/' . $document->fichier)))
                                                <li><strong>Taille :</strong> {{ number_format(filesize(storage_path('app/public/' . $document->fichier)) / 1024, 2) }} KB</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                <!-- Statut d'expiration -->
                                @if($document->date_expiration)
                                    <div class="card border-left-{{ \Carbon\Carbon::parse($document->date_expiration)->isPast() ? 'danger' : (\Carbon\Carbon::parse($document->date_expiration)->diffInDays() < 30 ? 'warning' : 'success') }} mt-3">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-calendar-alt mr-2"></i>
                                                Statut actuel
                                            </h6>
                                            @if(\Carbon\Carbon::parse($document->date_expiration)->isPast())
                                                <div class="text-danger">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    <strong>Expiré</strong>
                                                </div>
                                            @elseif(\Carbon\Carbon::parse($document->date_expiration)->diffInDays() < 30)
                                                <div class="text-warning">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    <strong>Expire bientôt</strong>
                                                </div>
                                            @else
                                                <div class="text-success">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    <strong>Valide</strong>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="card border-left-warning mt-3">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-bolt mr-2"></i>
                                            Actions rapides
                                        </h6>
                                        <div class="d-grid gap-2">
                                            @if($document->fichier)
                                                <a href="{{ route('individus.documents.download', [$individu, $document]) }}" class="btn btn-success btn-sm">
                                                    <i class="fas fa-download"></i> Télécharger
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

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer les modifications
                                </button>
                                <a href="{{ route('individus.documents.show', [$individu, $document]) }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
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

@push('scripts')
<script>
$(document).ready(function() {
    // Mise à jour du label du fichier
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName || 'Choisir un fichier...');
    });
});
</script>
@endpush
@endsection