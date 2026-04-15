@extends('layouts.admin')

@section('title', 'Ajouter un document - ' . $classeur->theme)

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

    /* Titres */
    .text-dark { color: var(--secondary-blue) !important; }
    .badge-soft-primary { 
        background-color: var(--soft-gold); 
        color: var(--primary-gold); 
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: bold;
    }

    /* Zone d'Aperçu & Upload */
    #preview-container {
        border: 2px solid #edf2f7;
        border-radius: 12px;
        background-color: #fcfcfc;
        height: 250px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
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
    .upload-zone.dragover { 
        border-color: #198754 !important; 
        background-color: rgba(25, 135, 84, 0.05); 
    }

    /* Boutons */
    .btn-primary {
        background-color: var(--secondary-blue) !important;
        border: none !important;
        padding: 0.8rem;
        border-radius: 8px;
    }
    .btn-primary:hover {
        background-color: #232d69 !important;
        transform: translateY(-2px);
    }

    .bi-primary { color: var(--primary-gold); }
</style>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('menages.show', $menage) }}">Ménage</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('menages.pochette.show', $menage) }}">Pochette</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('menages.classeurs.show', [$menage, $classeur]) }}">Classeur</a></li>
                    <li class="breadcrumb-item active">Nouveau document</li>
                </ol>
            </nav>
            <h2 class="font-weight-bold text-dark">
                <i class="bi bi-file-earmark-arrow-up bi-primary me-2"></i>Ajouter un document
            </h2>
            <p class="text-muted">Classeur : <span class="badge badge-soft-primary">{{ $classeur->theme }}</span></p>
        </div>
    </div>

    <form action="{{ route('menages.classeurs.documents.store', [$menage, $classeur]) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
        @csrf
        <div class="row">
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="fw-bold text-secondary mb-2">Nom du document <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-tag text-muted"></i></span>
                                    <input type="text" name="libelle" class="form-control @error('libelle') is-invalid @enderror" 
                                           placeholder="Ex: Acte de naissance" value="{{ old('libelle') }}" required>
                                </div>
                                @error('libelle') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="fw-bold text-secondary mb-2">Type de pièce <span class="text-danger">*</span></label>
                                <select name="type_document_id" class="form-select @error('type_document_id') is-invalid @enderror" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($typeDocuments as $type)
                                        <option value="{{ $type->id }}" {{ old('type_document_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="fw-bold text-secondary mb-2">Numéro de référence</label>
                                <input type="text" name="numero" class="form-control @error('numero') is-invalid @enderror" 
                                       placeholder="Ex: N° 001245" value="{{ old('numero') }}">
                            </div>

                            <div class="col-md-12" id="individu_selection" style="display:none;">
                                <div class="p-3 bg-light rounded border shadow-sm" style="border-left: 4px solid var(--primary-gold)">
                                    <label class="small fw-bold text-uppercase text-dark">Sélectionner le membre :</label>
                                    <select name="individu_menage_id" id="individu_menage_id" class="form-select select2">
                                        <option value="">-- Choisir un membre --</option>
                                        @foreach($individus as $individu)
                                            <option value="{{ $individu->id }}" {{ old('individu_menage_id') == $individu->id ? 'selected' : '' }}>
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
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="upload-zone p-4 rounded text-center mb-3" id="drop-zone">
                            <input type="file" name="fichier" id="fichier" 
                                   style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 5;" 
                                   accept=".pdf,.jpg,.jpeg,.png" required>
                            
                            <div id="upload-text">
                                <i class="bi bi-plus-circle-dotted fs-2 bi-primary"></i>
                                <p class="small fw-bold mb-0 mt-2 text-dark">Cliquer ou glisser le fichier</p>
                            </div>
                        </div>

                        <div id="file-details" class="small mb-3 d-none p-2 bg-light rounded border text-center">
                            <i class="bi bi-paperclip bi-primary me-1"></i> <span id="file-name" class="fw-bold text-truncate d-inline-block" style="max-width: 200px;"></span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                            <i class="bi bi-check2-circle me-2"></i> Enregistrer le document
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

            // 1. Si c'est une image (JPG, PNG)
            if (file.type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $placeholder.addClass('d-none');
                    $newPreviewImg.attr('src', e.target.result).removeClass('d-none');
                }
                reader.readAsDataURL(file);
            } 
            // 2. Si c'est un PDF
            else if (file.type === 'application/pdf') {
                $newPreviewImg.addClass('d-none');
                $placeholder.html('<i class="bi bi-file-earmark-pdf display-1 text-danger"></i><p class="small mt-2">' + file.name + '</p>').removeClass('d-none');
            }
            // 3. Autre type
            else {
                $newPreviewImg.addClass('d-none');
                $placeholder.html('<i class="bi bi-file-earmark-text display-1 text-secondary"></i><p class="small mt-2">' + file.name + '</p>').removeClass('d-none');
            }
        }
    });

    // Drag & Drop
    $('#drop-zone').on('dragover', function(e) { 
        e.preventDefault(); 
        $(this).addClass('dragover'); 
    });
    $('#drop-zone').on('dragleave', function(e) { 
        $(this).removeClass('dragover'); 
    });
    $('#drop-zone').on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            $fileInput[0].files = files;
            $fileInput.trigger('change');
        }
    });
});
</script>
@endpush
@endsection