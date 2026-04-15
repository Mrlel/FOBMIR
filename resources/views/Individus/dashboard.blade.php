<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DocuRural - Mon tableau de bord</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    :root {
      --earth: #5d4037;
      --earth-light: #8d6e63;
      --text-dark: #3e2723;
    }

    body {
      font-family: 'Open Sans', sans-serif;
      background-color: var(--beige);
      color: var(--text-dark);
    }

    .navbar-earth {
      background-color: var(--earth);
    }

    .navbar-earth .navbar-brand,
    .navbar-earth .nav-link {
      color: #fff !important;
    }

    .navbar-earth .nav-link:hover {
      color: #d7ccc8 !important;
    }

    .btn-earth {
      background-color: var(--earth);
      color: #fff;
      border: none;
    }

    .btn-earth:hover {
      background-color: var(--earth-light);
      color: #fff;
    }

    .alert-earth {
      background-color: #d7ccc8;
      color: var(--text-dark);
      border: none;
    }

    .card-document {
      border: none;
      border-radius: 0.75rem;
      background-color: #fff;
    }

    .icon-size {
      font-size: 1.5rem;
    }
  </style>
</head>

<body>
@include('layouts.message')
<!-- Navbar responsive -->
<nav class="navbar navbar-expand-lg navbar-earth">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><i class="bi bi-house-fill"></i> DocuRural</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="bi bi-person-circle"></i> Mon compte</a>
        </li>
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST">
                 @csrf
                <button type="submit" class="nav-link ml-2 text-sm font-medium earth-tone-text hover:text-amber-800"><i class="bi bi-box-arrow-right"></i> Se deconnecter</button>
            </form>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Contenu principal -->
<div class="container mt-4">
  <h2 class="mb-4"><i class="bi bi-folder2-open"></i> Mes documents</h2>

  <!-- Barre de recherche et boutons -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <input type="text" class="form-control" placeholder="Rechercher...">
    </div>
    <div class="col-md-3">
      <select class="form-select">
        <option selected>Tous les types</option>
        @foreach ($typeDocuments as $typeDocument)
            <option value="{{ $typeDocument->id }}">{{ $typeDocument->libelle }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <button class="btn btn-outline-secondary w-100"><i class="bi bi-funnel"></i> Filtrer</button>
    </div>
  </div>

  <!-- Compteur -->
  <div class="alert alert-earth d-flex align-items-center">
    <i class="bi bi-info-circle me-2"></i>
    <span>Vous avez <strong>{{ $documents->count() }} documents</strong> enregistrés.</span>
  </div>

  <div class="col-md-3">
    <button class="btn btn-earth w-100 mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-upload"></i> Téléverser</button>
  </div>

  <!-- Cartes documents -->
  <div class="row g-3">

    @foreach ($documents as $document)
    <div class="col-sm-12 col-md-6 col-lg-4">
      <div class="card card-document shadow-sm h-100">
        <div class="card-body d-flex flex-column">
          <h6 class="card-title"><i class="bi bi-file-earmark-text text-primary icon-size"></i> {{ $document->typeDocument->libelle }}</h6>
          <p class="text-muted small mb-2">Libelle : {{ $document->libelle }}</p>
          <p class="text-muted small mb-3">Ajouté le {{ $document->created_at->format('d/m/Y') }}</p>
          <div class="mt-auto">
            <a href="#" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-download"></i> Télécharger</a>
            <a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i> Imprimer</a>
          </div>
        </div>
      </div>
    </div>
    @endforeach



    <!-- Dupliquer ici d'autres cartes si besoin -->

  </div><!-- /row -->
</div><!-- /container -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('documents.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="libelle" class="form-label">Libelle</label>
                            <input type="text" class="form-control" id="libelle" name="libelle" required>
                        </div>
                        <div class="mb-3">
                            <label for="numero" class="form-label">Numero</label>
                            <input type="text" class="form-control" id="numero" name="numero">
                        </div>
                        <div class="mb-3">
                            <label for="fichier" class="form-label">Fichier</label>
                            <input type="file" class="form-control" id="fichier" name="fichier" required>
                        </div>
                        <div class="mb-3">
                            <label for="type_document_id" class="form-label">Type Document</label>
                            <select class="form-select" id="type_document_id" name="type_document_id" required>
                                <option value="">Sélectionner un type document</option>
                                @foreach ($typeDocuments as $typeDocument)
                                    <option value="{{ $typeDocument->id }}">{{ $typeDocument->libelle }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-earth w-100"> <i class="bi bi-upload"></i> Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>