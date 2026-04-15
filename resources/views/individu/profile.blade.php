@extends('layouts.individu')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <div class="bg-primary-green-soft p-3 rounded-circle me-3">
            <i class="bi bi-person-bounding-box fs-2 text-success"></i>
        </div>
        <div>
            <h2 class="fw-bold mb-0">Mon Profil</h2>
            <p class="text-muted mb-0 small">Gérez vos informations personnelles et vos paramètres de compte.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Informations Personnelles</h5>
                </div>
                <div class="card-body">
                    <form action="#" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-medium">Prénom</label>
                                <input type="text" class="form-control bg-light border-0" value="{{ $individu->prenom }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-medium">Nom</label>
                                <input type="text" class="form-control bg-light border-0" value="{{ $individu->nom }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-medium">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control bg-light border-0" value="{{ $individu->email }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-medium">Téléphone</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-telephone"></i></span>
                                    <input type="text" class="form-control bg-light border-0" value="{{ $individu->telephone }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-medium">Date de naissance</label>
                               <input type="date" class="form-control bg-light border-0" 
       value="{{ $individu->date_naissance ? $individu->date_naissance->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-medium">Sexe</label>
                                <select class="form-select bg-light border-0">
                                    <option value="M" {{ $individu->sexe == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ $individu->sexe == 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-muted small fw-medium">Profession</label>
                                <input type="text" class="form-control bg-light border-0" value="{{ $individu->profession }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small fw-medium">Adresse complète</label>
                                <textarea class="form-control bg-light border-0" rows="2">{{ $individu->adresse_complete }}</textarea>
                            </div>
                        </div>
                        
                        <hr class="my-4 opacity-25">
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-4 py-2 rounded-pill fw-medium">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="bi bi-clock-history text-muted fs-4"></i>
                    <p class="mb-1 mt-2 small text-muted">Dernière connexion</p>
                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($individu->derniere_connexion)->format('d/m/Y à H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary-green-soft {
        background-color: rgba(46, 204, 113, 0.15);
        width: fit-content;
    }
    .form-control:focus, .form-select:focus {
        background-color: #fff !important;
        border-color: #2ecc71 !important;
        box-shadow: 0 0 0 0.25rem rgba(46, 204, 113, 0.1);
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
</style>
@endsection