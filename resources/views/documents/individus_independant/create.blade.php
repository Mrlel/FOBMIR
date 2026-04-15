@extends('layouts.individu')

@section('content')

<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --light-bg: #f8f9fa;
    }

    .upload-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .form-label-custom {
        font-weight: 600;
        color: var(--secondary-blue);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .custom-input {
        border: 2px solid #eee;
        border-radius: 12px;
        padding: 0.75rem;
        transition: 0.3s;
    }

    .custom-input:focus {
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 0.25rem rgba(182, 140, 54, 0.1);
    }

    /* Zone de fichier stylisée */
    .file-drop-area {
        position: relative;
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: center;
        width: 100%;
        padding: 40px;
        border: 2px dashed #cbd5e0;
        border-radius: 15px;
        background-color: var(--light-bg);
        transition: 0.3s;
        cursor: pointer;
    }

    .file-drop-area:hover {
        border-color: var(--primary-gold);
        background-color: rgba(182, 140, 54, 0.05);
    }

    .file-icon {
        font-size: 3rem;
        color: var(--primary-gold);
        margin-bottom: 15px;
    }

    .btn-save-doc {
        background: var(--secondary-blue);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1rem 2.5rem;
        font-weight: 700;
        letter-spacing: 1px;
        transition: 0.3s;
    }

    .btn-save-doc:hover {
        background: var(--primary-gold);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(182, 140, 54, 0.3);
    }
</style>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-file-earmark-arrow-up text-primary-gold me-2"></i>Nouveau document
            </h3>
            <p class="text-muted mb-0 small">Classeur : <span class="badge bg-light text-secondary border">{{ $classeur->theme }}</span></p>
        </div>
        <a href="{{ route('individu.classeurs.show', $classeur) }}" class="btn btn-light border px-4 fw-bold shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card upload-card overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-7 p-4 p-md-5 bg-white">
                        <form action="{{ route('individu.classeurs.documents.store', $classeur) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label class="form-label-custom">Libellé du document <span class="text-danger">*</span></label>
                                    <input type="text" name="libelle" value="{{ old('libelle') }}" 
                                           class="form-control custom-input" placeholder="Ex: CNI Recto-Verso" required>
                                    @error('libelle')
                                        <div class="text-danger small mt-2 fw-bold">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label-custom">Type de document <span class="text-danger">*</span></label>
                                    <select name="type_document_id" class="form-select custom-input" required>
                                        <option value="">Sélectionner...</option>
                                        @foreach($typeDocuments as $type)
                                            <option value="{{ $type->id }}" @selected(old('type_document_id') == $type->id)>{{ $type->libelle }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label-custom">Numéro (si applicable)</label>
                                    <input type="text" name="numero" value="{{ old('numero') }}" 
                                           class="form-control custom-input" placeholder="N° de pièce">
                                </div>

                                <div class="col-md-12 mb-4">
                                    <label class="form-label-custom">Sélectionner le fichier <span class="text-danger">*</span></label>
                                    <div class="file-drop-area" id="dropArea">
                                        <i class="bi bi-cloud-arrow-up file-icon"></i>
                                        <span class="fw-bold text-dark">Cliquez ou glissez le fichier ici</span>
                                        <span class="text-muted small">PDF, JPG ou PNG (Max. 5Mo)</span>
                                        <input type="file" name="fichier" class="opacity-0 position-absolute w-100 h-100" 
                                               id="fileInput" required style="cursor: pointer;">
                                    </div>
                                    <div id="file-name" class="mt-2 text-primary-gold fw-bold small"></div>
                                    @error('fichier')
                                        <div class="text-danger small mt-2 fw-bold">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="btn btn-save-doc w-100 shadow-sm">
                                    <i class="bi bi-shield-lock-fill me-2"></i>ARCHIVER LE DOCUMENT
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-5 p-4 p-md-5" style="background: var(--secondary-blue); color: white;">
                        <h5 class="fw-bold mb-4 text-primary-gold">Instructions d'archivage</h5>
                        <ul class="list-unstyled small">
                            <li class="mb-4 d-flex">
                                <i class="bi bi-check-circle-fill text-primary-gold me-3 mt-1"></i>
                                <span>Assurez-vous que le document est <strong>lisible</strong> et non tronqué.</span>
                            </li>
                            <li class="mb-4 d-flex">
                                <i class="bi bi-check-circle-fill text-primary-gold me-3 mt-1"></i>
                                <span>Privilégiez le format <strong>PDF</strong> pour les documents multi-pages.</span>
                            </li>
                            <li class="mb-4 d-flex">
                                <i class="bi bi-check-circle-fill text-primary-gold me-3 mt-1"></i>
                                <span>Le nom du fichier sera automatiquement renommé pour le système.</span>
                            </li>
                        </ul>
                        
                        <div class="mt-5 p-3 rounded-4" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-shield-shaded fs-3 me-3 text-primary-gold"></i>
                                <div>
                                    <div class="fw-bold">Espace Sécurisé</div>
                                    <div class="opacity-75" style="font-size: 0.75rem;">Cryptage AES-256 activé</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Petit script pour afficher le nom du fichier sélectionné
    document.getElementById('fileInput').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        document.getElementById('file-name').innerHTML = '<i class="bi bi-file-earmark-check me-1"></i> Fichier sélectionné : ' + fileName;
        document.getElementById('dropArea').style.borderColor = 'var(--primary-gold)';
    });
</script>

@endsection