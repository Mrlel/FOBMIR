@extends('layouts.admin')

@section('title', 'Nouveau document')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-2"></i>
                        Créer un nouveau document
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('documents.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>

                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
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
                                        <option value="Contrat" {{ old('type') == 'Contrat' ? 'selected' : '' }}>Contrat</option>
                                        <option value="Facture" {{ old('type') == 'Facture' ? 'selected' : '' }}>Facture</option>
                                        <option value="Reçu" {{ old('type') == 'Reçu' ? 'selected' : '' }}>Reçu</option>
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
                                            <li><i class="fas fa-check text-success mr-1"></i> Scannez en haute qualité</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Types de fichiers -->
                                <div class="card border-left-success mt-3">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-file mr-2"></i>
                                            Formats supportés
                                        </h6>
                                        <div class="row text-center small">
                                            <div class="col-6">
                                                <i class="fas fa-file-pdf text-danger"></i> PDF<br>
                                                <i class="fas fa-file-word text-primary"></i> DOC/DOCX<br>
                                                <i class="fas fa-file-excel text-success"></i> XLS/XLSX
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-image text-info"></i> JPG/JPEG<br>
                                                <i class="fas fa-image text-warning"></i> PNG<br>
                                                <i class="fas fa-image text-secondary"></i> GIF
                                            </div>
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
                                    <i class="fas fa-save"></i> Créer le document
                                </button>
                                <a href="{{ route('documents.index') }}" class="btn btn-secondary ml-2">
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
});
</script>
@endpush
@endsection