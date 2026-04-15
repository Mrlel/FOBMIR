@extends('layouts.admin')

@section('content')
<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --light-gold: rgba(182, 140, 54, 0.1);
    }

    .bg-gradient-legal {
        background: linear-gradient(135deg, var(--secondary-blue) 0%, #2a357d 100%);
    }

    .text-gold { color: var(--primary-gold) !important; }

    .btn-gold {
        background-color: var(--primary-gold);
        color: white;
        border: none;
    }

    .btn-gold:hover {
        background-color: #a37b2f;
        color: white;
    }

    .avatar-circle {
        width: 40px;
        height: 40px;
        background-color: var(--light-gold);
        color: var(--primary-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: bold;
    }

    .badge-role {
        padding: 0.5em 0.8em;
        border-radius: 6px;
        font-size: 0.85rem;
    }
</style>

<div class="p-4">
    @include('layouts.message')

            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="display-6 fw-bold mb-1">Gestion des Points Focaux</h2>
                    <p class="lead mb-0 opacity-75">Gérez les accès et les affectations par village.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('users.create') }}" class="btn btn-gold btn-lg shadow-sm px-4">
                        <i class="bi bi-person-plus-fill me-2"></i>Nouvel utilisateur
                    </a>
                </div>
            </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-white py-3 border-0">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchInput" class="form-control bg-light border-0" placeholder="Rechercher par nom, téléphone, rôle...">
                    </div>
                </div>
                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    <span class="badge bg-light text-secondary border px-3 py-2">
                        <i class="bi bi-people-fill me-1 text-gold"></i> {{ $users->count() }} Utilisateurs au total
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="usersTable">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Utilisateur</th>
                            <th>Téléphone</th>
                            <th>Affectation (Village)</th>
                            <th>Rôle</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        {{ strtoupper(substr($user->nom, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $user->nom }} {{ $user->prenom }}</div>
                                        <small class="text-muted">{{ $user->email ?? 'Sans email' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-dark fw-medium">
                                    <i class="bi bi-telephone me-1 opacity-50"></i> {{ $user->telephone ?? '—' }}
                                </span>
                            </td>
                            <td>
                                @if($user->village)
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-geo-alt-fill text-gold me-1"></i>{{ $user->village->nom }}
                                    </span>
                                @else
                                    <span class="text-muted small">Non assigné</span>
                                @endif
                            </td>
                            <td>
                                @if($user->role === 'point_focal')
                                    <span class="badge-role bg-info text-white">
                                        <i class="bi bi-pin-map-fill me-1"></i> Point Focal
                                    </span>
                                @else
                                    <span class="badge-role bg-dark text-white">
                                        <i class="bi bi-shield-lock-fill me-1"></i> Administrateur
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-white border" title="Détails">
                                        <i class="bi bi-eye text-info"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-white border" title="Modifier">
                                        <i class="bi bi-pencil-square text-warning"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-white border" 
                                            onclick="if(confirm('Supprimer cet utilisateur ?')) document.getElementById('delete-form-{{ $user->id }}').submit();">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" class="d-none">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr id="noResult">
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                                Aucun utilisateur trouvé
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#usersTable tbody tr:not(#noResult)');
        let hasMatch = false;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const match = text.includes(filter);
            row.style.display = match ? '' : 'none';
            if (match) hasMatch = true;
        });

        // Gestion de l'affichage "Aucun résultat"
        let noResultMsg = document.getElementById('noResult');
        if (!hasMatch && !noResultMsg) {
            const tr = document.createElement('tr');
            tr.id = 'noResult';
            tr.innerHTML = `<td colspan="5" class="text-center py-5 text-muted">Aucun utilisateur ne correspond à votre recherche.</td>`;
            document.querySelector('#usersTable tbody').appendChild(tr);
        } else if (hasMatch && noResultMsg) {
            noResultMsg.remove();
        }
    });
</script>
@endsection