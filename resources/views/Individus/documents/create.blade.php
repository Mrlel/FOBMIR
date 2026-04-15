@extends('layouts.admin')

@section('title', 'Nouveau document pour ' . $individu->prenom . ' ' . $individu->nom)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-2"></i>
                        Nouveau document pour {{ $individu->prenom }} {{ $individu->nom }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('individus.documents.index', $individu) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour aux documents
                        </a>
                    </div>
                </div>

                <form action="{{ route('individus.documents.store', $individu) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Informations de l'individu -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h5><i class="fas fa-user mr-2"></i>Individu concerné</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Nom complet :</strong> {{ $individu->prenom }} {{ $individu->nom }}</p>
                                            @if($individu->telephone)
                                                <p class="mb-1"><strong>Téléphone :</strong> {{ $individu->telephone }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            @if($individu->menage)
                                                <p class="mb-1"><strong>Ménage :</strong> {{ $individu->menage->nom_chef }}</p>
                                            @endif
                                            <p class="mb-0"><strong>Documents existants :</strong> {{ $individu->documents->count() }}</p>
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
                                           value="{{ old('nom') }}" 
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
                                        <option value="Carte d'identité" {{ old('type') == 'Carte d\'identité' ? 'selected' : '' }}>Carte d'identité</option>
                                        <option value="Passeport" {{ old('type') == 'Passeport' ? 'selected' : '' }}>Passeport</option>
                                        <option value="Acte de naissance" {{ old('type') == 'Acte de naissance' ? 'selected' : '' }}>Acte de naissance</option>
                                        <option value="Certificat de mariage" {{ old('type') == 'Certificat de mariage' ? 'selected' : '' }}>Certificat de mariage</option>
                                        <option value="Diplôme" {{ old('type') == 'Diplôme' ? 'selected' : '' }}>Diplôme</option>
                                        <option value="Certificat médical" {{ old('type') == 'Certificat médical' ? 'selected' : '' }}>Certificat médical</option>
                                        <option value="Contrat de travail" {{ old('type') == 'Contrat de travail' ? 'selected' : '' }}>Contrat de travail</option>
                                        <option value="Attestation" {{ old('type') == 'Attestation' ? 'selected' : '' }}>Attestation</option>
                                        <option value="Certificat de scolarité" {{ old('type') == 'Certificat de scolarité' ? 'selected' : '' }}>Certificat de scolarité</option>
                                        <option value="Permis de conduire" {{ old('type') == 'Permis de conduire' ? 'selected' : '' }}>Permis de conduire</option>
                                        <option value="Autre" {{ old('type') == 'Autre' ? 'selected' : '' }}>Autre</option>
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
                                           value="{{ old('numero_document') }}"
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
                                           value="{{ old('date_emission') }}">
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
                                           value="{{ old('date_expiration') }}">
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
                                              placeholder="Description détaillée du document (optionnel)">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Fichier -->
                                <div class="form-group">
                                    <label for="fichier">Fichier du document</label>
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
                                        Formats acceptés : PDF, DOC, DOCX, JPG, JPEG, PNG, GIF, XLS, XLSX. Taille max : 10MB
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Aide -->
                                <div class="card border-left-info">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-lightbulb mr-2"></i>
                                            Conseils
                                        </h6>
                                        <ul class="list-unstyled small">
                                            <li><i class="fas fa-check text-success mr-1"></i> Utilisez un nom descriptif</li>
                                            <li><i class="fas fa-check text-success mr-1"></i> Spécifiez le type pour un meilleur classement</li>
                                            <li><i class="fas fa-check text-success mr-1"></i> Ajoutez le numéro si applicable</li>
                                            <li><i class="fas fa-check text-success mr-1"></i> Indiquez les dates importantes</li>
                                            <li><i class="fas fa-check text-success mr-1"></i> Scannez en haute qualité</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Types de documents courants -->
                                <div class="card border-left-primary mt-3">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-list mr-2"></i>
                                            Documents courants
                                        </h6>
                                        <ul class="list-unstyled small">
                                            <li><i class="fas fa-id-card mr-1 text-primary"></i> Pièces d'identité</li>
                                            <li><i class="fas fa-graduation-cap mr-1 text-success"></i> Diplômes et certificats</li>
                                            <li><i class="fas fa-heartbeat mr-1 text-danger"></i> Documents médicaux</li>
                                            <li><i class="fas fa-briefcase mr-1 text-warning"></i> Documents professionnels</li>
                                            <li><i class="fas fa-home mr-1 text-info"></i> Documents familiaux</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Aperçu -->
                                <div class="card border-left-success mt-3" id="apercu" style="display: none;">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-eye mr-2"></i>
                                            Aperçu
                                        </h6>
                                        <p class="mb-1"><strong>Document :</strong></p>
                                        <p class="text-primary" id="apercu-nom">-</p>
                                        <p class="mb-1"><strong>Type :</strong></p>
                                        <p class="text-info" id="apercu-type">-</p>
                                        <p class="mb-1"><strong>Propriétaire :</strong></p>
                                        <p class="text-secondary">{{ $individu->prenom }} {{ $individu->nom }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Créer le document
                                </button>
                                <a href="{{ route('individus.documents.index', $individu) }}" class="btn btn-secondary ml-2">
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

@push('scripts')
<script>
$(document).ready(function() {
    // Mise à jour du label du fichier
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName || 'Choisir un fichier...');
    });

    // Mise à jour de l'aperçu
    function updateApercu() {
        const nom = $('#nom').val();
        const type = $('#type').val();
        const apercu = $('#apercu');
        const apercuNom = $('#apercu-nom');
        const apercuType = $('#apercu-type');
        
        if (nom || type) {
            apercuNom.text(nom || 'Non défini');
            apercuType.text(type || 'Non spécifié');
            apercu.show();
        } else {
            apercu.hide();
        }
    }
    
    // Événements
    $('#nom, #type').on('change keyup', updateApercu);
    
    // Mise à jour initiale
    updateApercu();
});
</script>
@endpush
@endsection