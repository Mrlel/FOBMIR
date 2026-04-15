@extends('layouts.admin')

@section('title', 'Modifier le document - ' . $document->libelle)

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --soft-gold: rgba(182, 140, 54, 0.1);
    }

    /* Fil d'Ariane */
    .breadcrumb-item a { color: var(--primary-gold); text-decoration: none; }
    .breadcrumb-item.active { color: var(--secondary-blue); }

    /* Cards */
    .card { border-radius: 12px; border: none; }
    
    /* Toggle Buttons Style */
    .btn-check:checked + .btn-outline-gold {
        background-color: var(--primary-gold) !important;
        color: white !important;
        border-color: var(--primary-gold) !important;
    }
    .btn-outline-gold {
        border-color: #dee2e6;
        color: #495057;
    }
    .btn-outline-gold:hover { background-color: var(--soft-gold); }

    /* Zone d'Upload */
    #preview-container {
        border: 2px solid #edf2f7;
        border-radius: 12px;
        background-color: #fcfcfc;
        height: 250px;
        position: relative;
        overflow: hidden;
    }

    .upload-zone {
        transition: all 0.3s ease;
        background-color: rgba(23, 30, 76, 0.03);
        border: 2px dashed #cbd5e0 !important;
        position: relative;
        cursor: pointer;
    }
    .upload-zone:hover { 
        border-color: var(--primary-gold) !important; 
        background-color: #fff;
    }

    .btn-gold { background-color: var(--primary-gold); color: white; border: none; padding: 0.8rem; }
    .btn-gold:hover { background-color: #a37d2f; color: white; }
</style>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('menages.show', $menage) }}">Ménage</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('menages.classeurs.show', [$menage, $classeur]) }}">Classeur</a></li>
                    <li class="breadcrumb-item active">Modifier document</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark">
                <i class="bi bi-pencil-square text-gold me-2"></i>Modifier le document
            </h2>
        </div>
    </div>

    <form action="{{ route('menages.classeurs.documents.update', [$menage, $classeur, $document]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="fw-bold text-secondary mb-2">Nom du document <span class="text-danger">*</span></label>
                                <input type="text" name="libelle" class="form-control @error('libelle') is-invalid @enderror" 
                                       value="{{ old('libelle', $document->libelle) }}" required>
                                @error('libelle') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="fw-bold text-secondary mb-2">Type de pièce <span class="text-danger">*</span></label>
                                <select name="type_document_id" class="form-select" required>
                                    @foreach($typeDocuments as $type)
                                        <option value="{{ $type->id }}" {{ old('type_document_id', $document->type_document_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="fw-bold text-secondary mb-2">Numéro de référence</label>
                                <input type="text" name="numero" class="form-control" value="{{ old('numero', $document->numero) }}">
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="fw-bold text-secondary mb-2 d-block">Le document concerne :</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="concerne_type" id="type_menage" value="menage" {{ !$document->individu_menage_id ? 'checked' : '' }}>
                                    <label class="btn btn-outline-gold py-2" for="type_menage">
                                        <i class="bi bi-people me-1"></i> Ménage complet
                                    </label>

                                    <input type="radio" class="btn-check" name="concerne_type" id="type_individu" value="individu" {{ $document->individu_menage_id ? 'checked' : '' }}>
                                    <label class="btn btn-outline-gold py-2" for="type_individu">
                                        <i class="bi bi-person me-1"></i> Membre spécifique
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12" id="individu_selection" style="{{ !$document->individu_menage_id ? 'display:none;' : '' }}">
                                <div class="p-3 bg-light rounded border border-warning shadow-sm">
                                    <label class="small fw-bold text-uppercase mb-2 d-block">Sélectionner le membre :</label>
                                    <select name="individu_menage_id" class="form-select">
                                        <option value="">-- Choisir un membre --</option>
                                        @foreach($individus as $individu)
                                            <option value="{{ $individu->id }}" {{ old('individu_menage_id', $document->individu_menage_id) == $individu->id ? 'selected' : '' }}>
                                                {{ $individu->nom }} {{ $individu->prenom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white pt-4">
                        <h6 class="fw-bold mb-0">Gestion du fichier</h6>
                    </div>
                    <div class="card-body">
                        <div id="preview-container" class="mb-3 d-flex align-items-center justify-content-center">
                            @php
                                $extension = pathinfo($document->fichier, PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp

                            <div id="preview-placeholder" class="text-center p-2">
                                @if($isImage && $document->fichier)
                                    <img src="{{ asset('storage/' . $document->fichier) }}" class="img-fluid h-100" style="max-height: 230px; object-fit: contain;">
                                @elseif($document->fichier)
                                    <i class="bi bi-file-earmark-pdf display-1 text-danger"></i>
                                    <p class="small text-muted mt-2">{{ basename($document->fichier) }}</p>
                                @else
                                    <i class="bi bi-cloud-slash display-1 text-muted opacity-25"></i>
                                    <p class="small text-muted">Aucun fichier</p>
                                @endif
                            </div>
                            
                            <img src="" id="new-preview-img" class="img-fluid h-100 w-100 d-none" style="object-fit: contain; background: white; z-index: 2;">
                        </div>

                        <div class="upload-zone p-4 rounded text-center mb-3" id="drop-zone">
                            <input type="file" name="fichier" id="fichier" 
                                   style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 5;" 
                                   accept=".pdf,.jpg,.jpeg,.png">
                            
                            <div id="upload-text">
                                <i class="bi bi-arrow-repeat fs-2 text-gold"></i>
                                <p class="small fw-bold mb-0 mt-2">Remplacer le fichier actuel</p>
                            </div>
                        </div>

                        <div id="file-details" class="small mb-3 d-none p-2 bg-light rounded border text-center">
                            <i class="bi bi-check-circle-fill text-success me-1"></i> <span id="file-name" class="fw-bold"></span>
                        </div>

                        <button type="submit" class="btn btn-gold w-100 fw-bold shadow-sm">
                            <i class="bi bi-save me-2"></i> Mettre à jour le document
                        </button>
                        
                        <a href="{{ route('menages.classeurs.show', [$menage, $classeur]) }}" class="btn btn-link w-100 text-muted mt-2">Annuler</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const $fileInput = $('#fichier');
    const $newPreviewImg = $('#new-preview-img');
    const $placeholder = $('#preview-placeholder');
    const $details = $('#file-details');

    $fileInput.on('change', function() {
        const file = this.files[0];
        
        if (file) {
            $('#file-name').text(file.name);
            $details.removeClass('d-none');
            $placeholder.addClass('d-none'); // On cache l'ancien fichier

            if (file.type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $newPreviewImg.attr('src', e.target.result).removeClass('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                $newPreviewImg.addClass('d-none');
                let icon = file.type === 'application/pdf' ? 'bi-file-earmark-pdf text-danger' : 'bi-file-earmark-text text-primary';
                $placeholder.html('<i class="bi ' + icon + ' display-1"></i><p class="small mt-2">' + file.name + '</p>').removeClass('d-none');
            }
        }
    });

    // Toggle membre/ménage
    $('input[name="concerne_type"]').on('change', function() {
        if ($(this).val() === 'individu') {
            $('#individu_selection').slideDown();
        } else {
            $('#individu_selection').slideUp();
        }
    });
});
</script>
@endpush
@endsection