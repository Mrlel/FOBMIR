@extends('layouts.admin')

@section('extra_css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
    :root {
        --primary: #f59e0b;
        --primary-dark: #d97706;
        --secondary: #1e3a5f;
        --dark: #0d1b2a;
        --light: #f1f5f9;
        --accent-blue: #3b82f6;
    }

    body { background-color: #f8fafc; }

    /* En-tête stylisé */
    .page-header-vibrant {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        border-left: 8px solid var(--primary);
    }

    /* Cards Personnalisées */
    .info-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        background: white;
        transition: transform 0.3s ease;
    }
    .info-card:hover { transform: translateY(-5px); }

    .card-header-vibrant {
        padding: 1.2rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        color: white;
    }

    .bg-grad-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); }
    .bg-grad-secondary { background: linear-gradient(135deg, var(--secondary) 0%, #334155 100%); }
    .bg-grad-dark { background: linear-gradient(135deg, var(--dark) 0%, var(--secondary) 100%); }

    /* Tableaux de détails */
    .table-details th {
        color: var(--secondary);
        font-weight: 600;
        font-size: 0.9rem;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-details td {
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
        color: var(--dark);
    }

    /* Boutons */
    .btn-action {
        border-radius: 12px;
        padding: 0.6rem 1.2rem;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    .btn-amber { background-color: var(--primary); color: var(--dark); border: none; }
    .btn-amber:hover { background-color: var(--primary-dark); transform: scale(1.05); }
    
    .btn-navy { background-color: var(--secondary); color: white; border: none; }
    .btn-navy:hover { background-color: var(--dark); transform: scale(1.05); }

    .badge-vibrant {
        padding: 0.5em 1em;
        border-radius: 50px;
        font-weight: 700;
        background: var(--primary-soft);
        color: var(--primary-dark);
        border: 1px solid var(--primary);
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    
    <div class="page-header-vibrant d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-black text-dark mb-0">
                <i class="bi bi-person-badge text-primary me-2"></i>Détails de l'Individu
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Ménage</a></li>
                    <li class="breadcrumb-item active fw-bold text-secondary">{{ $individuMenage->nom }}</li>
                </ol>
            </nav>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('admin.individus-menage.index') }}" class="btn btn-action btn-outline-secondary rounded-pill">
                <i class="bi bi-arrow-left"></i>
            </a>
            <a href="{{ route('admin.individus-menage.edit', $individuMenage) }}" class="btn btn-action btn-amber shadow-sm">
                <i class="bi bi-pencil-square me-2"></i>Modifier
            </a>
            <form action="{{ route('admin.individus-menage.destroy', $individuMenage) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-action btn-danger shadow-sm">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 fade show d-flex align-items-center">
            <i class="bi bi-check-circle-fill fs-4 me-3"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="info-card">
                <div class="card-header-vibrant bg-grad-secondary">
                    <i class="bi bi-person-vcard fs-4 me-3"></i>Identité & Contact
                </div>
                <div class="card-body p-4">
                    <table class="table table-details table-borderless mb-0">
                        <tr>
                            <th width="35%">Nom Complet</th>
                            <td class="fw-bold fs-5 text-secondary">{{ $individuMenage->nom }} {{ $individuMenage->prenom }}</td>
                        </tr>
                        <tr>
                            <th>Naissance</th>
                            <td>
                                <span class="text-dark fw-bold">
                                    {{ $individuMenage->date_naissance ? \Carbon\Carbon::parse($individuMenage->date_naissance)->format('d F Y') : 'N/A' }}
                                </span>
                                <br><small class="text-muted">à {{ $individuMenage->lieu_naissance }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Téléphone</th>
                            <td class="text-primary fw-bold"><i class="bi bi-telephone me-2"></i>{{ $individuMenage->telephone ?? 'Non renseigné' }}</td>
                        </tr>
                        <tr>
                            <th>Profession / Emploi</th>
                            <td><span class="badge bg-light text-dark border px-3 py-2 rounded-pill">{{ $individuMenage->emploi ?? 'Sans emploi' }}</span></td>
                        </tr>
                        <tr>
                            <th>Numéro de Pièce</th>
                            <td><span class="badge bg-grad-primary text-dark fw-black">{{ $individuMenage->numpiece }}</span></td>
                        </tr>
                        <tr>
                            <th>Extrait de naissance</th>
                            <td class="font-monospace text-muted">{{ $individuMenage->num_extrait_naissance }}</td>
                        </tr>
                    </table>
                    
                    @if($individuMenage->doc_piece)
                    <div class="mt-4 p-3 rounded-4 bg-light d-flex align-items-center justify-content-between border">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-file-earmark-pdf-fill text-danger fs-2 me-3"></i>
                            <div>
                                <div class="fw-bold">Document d'identité</div>
                                <small class="text-muted">Format Numérisé</small>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $individuMenage->doc_piece) }}" target="_blank" class="btn btn-navy rounded-pill px-4 btn-sm">
                            <i class="bi bi-eye me-1"></i> Consulter
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="row g-4">
                <div class="col-12">
                    <div class="info-card">
                        <div class="card-header-vibrant bg-grad-primary text-dark">
                            <i class="bi bi-geo-alt-fill fs-4 me-3"></i>Localisation
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex mb-3 align-items-center">
                                <div class="p-3 bg-light rounded-circle me-3">
                                    <i class="bi bi-map text-primary fs-4"></i>
                                </div>
                                <div>
                                    <div class="small text-muted text-uppercase fw-bold">Région / District</div>
                                    <div class="fw-bold text-dark">
                                        {{ $individuMenage->menage->sousQuartier->quartier->village->sousPrefecture->departement->region->nom ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <hr class="opacity-10">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <small class="text-muted d-block">Village / Commune</small>
                                    <span class="fw-bold">{{ $individuMenage->menage->sousQuartier->quartier->village->nom ?? 'N/A' }}</span>
                                </div>
                                <div class="col-6 mb-3">
                                    <small class="text-muted d-block">Quartier</small>
                                    <span class="fw-bold text-secondary">{{ $individuMenage->menage->sousQuartier->quartier->nom ?? 'N/A' }}</span>
                                </div>
                                <div class="col-12">
                                    <div class="p-2 bg-light rounded text-center border-start border-4 border-warning">
                                        <i class="bi bi-geo-fill me-2 opacity-50"></i>{{ $individuMenage->menage->sousQuartier->nom ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="info-card border-top border-5 border-secondary">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold mb-0 text-secondary"><i class="bi bi-house-door-fill me-2"></i>Rattachement Ménage</h5>
                                <span class="badge bg-dark rounded-pill">{{ $individuMenage->menage->nb_individus ?? '0' }} Membres</span>
                            </div>
                            <div class="p-3 rounded-4" style="background: rgba(30, 58, 95, 0.05);">
                                <small class="text-muted d-block uppercase">Chef de ménage</small>
                                <div class="h5 fw-bold text-dark mb-0">{{ $individuMenage->menage->nom_chef ?? 'N/A' }}</div>
                                <div class="mt-2">
                                    <span class="badge bg-white text-secondary border rounded-pill px-3">
                                        <i class="bi bi-gender-ambiguous me-1"></i> 
                                        {{ $individuMenage->menage->sexe_chef == 'M' ? 'Masculin' : 'Féminin' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection