@extends('layouts.admin')

@section('content')
<style>
    .info-card {
        background: white;
        border-radius: 5px;
        padding: 25px;
        margin-bottom: 5px;
    }

    .info-card-header {
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .info-card-header h5 {
        color: #f77f00;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-card-header i {
        font-size: 24px;
    }

    .info-item {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
        min-width: 180px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-label i {
        color: #f77f00;
        width: 20px;
    }

    .info-value {
        color: #212529;
        flex: 1;
    }

    .info-value.empty {
        color: #6c757d;
        font-style: italic;
    }

    .location-breadcrumb {
        padding: 10px;
        border-radius: 10px;
        margin-bottom: 20px;
        background:  #009E60 ;
    }

    .location-breadcrumb .breadcrumb-item {
        color: rgba(255,255,255,0.8);
    }

    .location-breadcrumb .breadcrumb-item.active {
        color: white;
        font-weight: 600;
    }

    .location-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        content: "→";
        color: rgba(255,255,255,0.6);
        padding: 0 10px;
    }

    .badge-role {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .badge-role.superadmin {
        background: linear-gradient(135deg, #4e4c4eff 0%, #1f1f1fff 100%);
        color: white;
    }

    .badge-role.admin {
        background: linear-gradient(135deg, #102e47ff 0%, #2c5153ff 100%);
        color: white;
    }

    .badge-role.user {
      border: 1px solid green;
      color: green;
        
    }

    .document-preview {
        max-width: 100%;
        max-height: 400px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .section-title {
        font-size: 28px;
        font-weight: 700;
        color: #212529;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .section-title i {
        color: #007bff;
    }

    .age-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>

<div class="container-fluid">
    <!-- En-tête avec boutons d'action -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <p class="text-muted mb-0"><i class="fas fa-user-circle text-primary"></i> Informations complètes et géolocalisation de l'utilisateur </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Modifier
            </a>
        </div>
    </div>

    @include('layouts.message')

    <!-- Informations personnelles -->
    <div class="info-card">
        <div class="info-card-header">
            <h5><i class="fas fa-user"></i> Informations personnelles</h5>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-signature"></i> Nom complet
                    </div>
                    <div class="info-value">
                        <strong>{{ $user->nom }} {{ $user->prenom }}</strong>
                    </div>
                </div>
            </div>
 
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-briefcase"></i> Emploi
                    </div>
                    <div class="info-value">
                        {{ $user->emploi ?? '' }}
                        @if(!$user->emploi)
                            <span class="empty">Non renseigné</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-user-shield"></i> Rôle
                    </div>
                    <div class="info-value">
                        <span class="badge-role {{ $user->role }}">
                            @if($user->role == 'superadmin')
                                Super Administrateur
                            @elseif($user->role == 'admin')
                                Administrateur
                            @else
                                Utilisateur
                            @endif
                        </span>
                    </div>
                </div>
                  <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-phone"></i> Téléphone
                    </div>
                    <div class="info-value">
                        <a href="tel:{{ $user->telephone }}" class="text-decoration-none">
                            {{ $user->telephone }}
                        </a>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-map-marker-alt"></i> Village
                    </div>
                    <div class="info-value">
                        {{ optional($user->village)->nom ?? '—' }}
                    </div>
                </div>
        </div>
    </div>

@endsection

