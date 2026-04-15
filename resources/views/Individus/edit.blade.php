@extends('layouts.admin')

@section('content')
<style>
    :root {
        --primary-gold: #b68c36;
        --secondary-blue: #171e4c;
        --light-bg: #f8f9fa;
    }

    /* En-tête */
    .h3 {
        color: var(--secondary-blue);
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    /* Style du formulaire */
    #wizardForm {
        background: white;
        padding: 1rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
    }

    .form-label {
        font-weight: 600;
        color: var(--secondary-blue);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.65rem 1rem;
        border: 1px solid #dce1e7;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 0.15rem rgba(182, 140, 54, 0.1);
    }

    /* Alert Style Localisation */
    .alert-info {
        background-color: #fdfaf3;
        border: none;
        border: 2px solid var(--primary-gold);
        color: var(--secondary-blue);
        border-radius: 12px;
    }

    .alert-info h6 {
        color: var(--primary-gold);
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
    }

    /* Boutons */
    .btn-success {
        background-color: var(--secondary-blue) !important;
        border: none !important;
        padding: 0.7rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background-color: #2a357d !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(23, 30, 76, 0.2);
    }

    .btn-secondary {
        background-color: #e2e8f0;
        color: #475569;
        border: none;
        font-weight: 600;
        border-radius: 8px;
    }

    .btn-secondary:hover {
        background-color: #cbd5e1;
        color: #1e293b;
    }

    .btn-outline-primary.btn-create-new {
        border-color: var(--primary-gold);
        color: var(--primary-gold);
    }

    .btn-outline-primary.btn-create-new:hover {
        background-color: var(--primary-gold);
        color: white;
    }

    /* Selecteurs avec bouton */
    .select-with-btn {
        display: flex;
        gap: 8px;
    }

    .select-with-btn .form-select {
        flex: 1;
    }
</style>

    @include('layouts.message')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-edit me-2 text-gold" style="color: var(--primary-gold)"></i> Modifier un individu ménage
        </h1>
        <a href="{{ route('individus.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>

    <form action="{{ route('individus.update', $individu->id) }}" method="POST" enctype="multipart/form-data" id="wizardForm" novalidate>
        @csrf
        @method('PUT')
        
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" 
                       value="{{ old('nom', $individu->nom) }}" required>
                @error('nom')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                <input type="text" name="prenom" id="prenom" class="form-control @error('prenom') is-invalid @enderror" 
                       value="{{ old('prenom', $individu->prenom) }}" required>
                @error('prenom')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="date_naissance" class="form-label">Date de naissance <span class="text-danger">*</span></label>
                <input type="date" name="date_naissance" id="date_naissance" 
                       class="form-control @error('date_naissance') is-invalid @enderror" 
                       value="{{ old('date_naissance', $individu->date_naissance) }}" required>
                @error('date_naissance')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="lieu_naissance" class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                <input type="text" name="lieu_naissance" id="lieu_naissance" 
                       class="form-control @error('lieu_naissance') is-invalid @enderror" 
                       value="{{ old('lieu_naissance', $individu->lieu_naissance) }}" required>
                @error('lieu_naissance')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="tel" name="telephone" id="telephone" 
                       class="form-control @error('telephone') is-invalid @enderror" 
                       value="{{ old('telephone', $individu->telephone) }}">
                @error('telephone')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="emploi" class="form-label">Emploi</label>
                <input type="text" name="emploi" id="emploi" class="form-control" 
                       value="{{ old('emploi', $individu->emploi) }}">
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label for="numpiece" class="form-label">Numéro de pièce <span class="text-danger">*</span></label>
                <input type="text" name="numpiece" id="numpiece" 
                       class="form-control @error('numpiece') is-invalid @enderror" 
                       value="{{ old('numpiece', $individu->numpiece) }}" required>
                @error('numpiece')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="num_extrait_naissance" class="form-label">N° extrait de naissance <span class="text-danger">*</span></label>
                <input type="text" name="num_extrait_naissance" id="num_extrait_naissance" 
                       class="form-control @error('num_extrait_naissance') is-invalid @enderror" 
                       value="{{ old('num_extrait_naissance', $individu->num_extrait_naissance) }}" required>
                @error('num_extrait_naissance')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-12">
                <label for="doc_piece" class="form-label">Document de pièce d'identité</label>
                <input type="file" name="doc_piece" id="doc_piece" class="form-control mb-2" 
                       accept="image/*,application/pdf" onchange="previewFile()">
                
                @if($individu->doc_piece)
                <div class="p-3 border rounded bg-light d-inline-block w-100">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-alt fa-2x text-info me-3"></i>
                        <div>
                            <a href="{{ asset('storage/' . $individu->doc_piece) }}" target="_blank" class="text-decoration-none fw-bold text-info">
                                Consulter le document actuel
                            </a>
                            @if($individu->nom_piece)
                                <span class="d-block small text-muted">{{ $individu->nom_piece }}</span>
                            @endif
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block italic">NB: Laissez vide si vous ne souhaitez pas modifier ce document.</small>
                </div>
                @endif
                <div id="preview" class="mt-2"></div>
            </div>
        </div>
        
        <hr class="my-5 border-light">

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <label class="form-label">Quartier</label>
                <div class="select-with-btn">
                    <select id="quartier_id" class="form-select" disabled>
                        <option value="">-- Sélectionner --</option>
                    </select>
                    <button type="button" class="btn btn-outline-primary btn-create-new" 
                            onclick="openCreateModal('quartier')" id="btn-create-quartier" disabled>
                         <i class="bi bi-plus"></i> ajouter
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Sous-quartier</label>
                <div class="select-with-btn">
                    <select id="sous_quartier_id" class="form-select" disabled>
                        <option value="">-- Sélectionner --</option>
                    </select>
                    <button type="button" class="btn btn-outline-primary btn-create-new" 
                            onclick="openCreateModal('sous-quartier')" id="btn-create-sous-quartier" disabled>
                        <i class="bi bi-plus"></i> ajouter
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <label for="menage_id" class="form-label">Ménage <span class="text-danger">*</span></label>
                <div class="select-with-btn">
                    <select name="menage_id" id="menage_id" class="form-select @error('menage_id') is-invalid @enderror" required disabled>
                        <option value="">-- Sélectionner un ménage --</option>
                    </select>
                    <button type="button" class="btn btn-outline-primary btn-create-new" 
                            onclick="openCreateModal('menage')" id="btn-create-menage" disabled>
                        <i class="bi bi-plus"></i> ajouter
                    </button>
                </div>
                @error('menage_id')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="alert alert-info mt-4">
            <h6><i class="fas fa-map-marker-alt me-2"></i> Géolocalisation rattachée</h6>
            <p class="mb-0">
                <strong>Village :</strong> {{ $user->village->nom ?? 'Non défini' }}<br>
                <small class="opacity-75 italic">Les informations de localisation sont héritées du point focal en charge.</small>
            </p>
        </div>
        
        <div class="d-flex justify-content-end mt-5 gap-3">
            <a href="{{ route('individus.index') }}" class="btn btn-secondary px-4">
                <i class="fas fa-times me-2"></i> Annuler
            </a>
            <button type="submit" class="btn btn-success px-4">
                <i class="fas fa-save me-2"></i> Mettre à jour l'individu
            </button>
        </div>
    </form>


<!-- Modal pour créer Quartier -->
<div class="modal fade" id="modalCreateQuartier" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Créer un nouveau quartier</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCreateQuartier">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom du quartier <span class="text-danger">*</span></label>
                            <input type="text" id="quartier_nom_new" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type de quartier</label>
                            <select id="quartier_type_quartier_id_new" class="form-select">
                                <option value="">-- Sélectionner (optionnel) --</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Historique</label>
                            <textarea id="quartier_historique_new" class="form-control" rows="3" placeholder="Historique du quartier (optionnel)"></textarea>
                        </div>
                    </div>
                    <input type="hidden" id="quartier_village_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="createQuartier()">
                    <span id="loading-quartier" style="display:none;" class="loading-spinner me-2"></span>
                    Créer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour créer Sous-Quartier -->
<div class="modal fade" id="modalCreateSousQuartier" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Créer un nouveau sous-quartier</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCreateSousQuartier">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom du sous-quartier <span class="text-danger">*</span></label>
                            <input type="text" id="sous_quartier_nom_new" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type de sous-quartier</label>
                            <select id="sous_quartier_type_sous_quartier_id_new" class="form-select">
                                <option value="">-- Sélectionner (optionnel) --</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Historique</label>
                            <textarea id="sous_quartier_historique_new" class="form-control" rows="3" placeholder="Historique du sous-quartier (optionnel)"></textarea>
                        </div>
                    </div>
                    <input type="hidden" id="sous_quartier_quartier_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="createSousQuartier()">
                    <span id="loading-sous-quartier" style="display:none;" class="loading-spinner me-2"></span>
                    Créer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour créer Ménage -->
<div class="modal fade" id="modalCreateMenage" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Créer un nouveau ménage</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCreateMenage">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom du chef de ménage <span class="text-danger">*</span></label>
                            <input type="text" id="menage_nom_chef_new" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre d'individus</label>
                            <input type="number" id="menage_nb_individus_new" class="form-control" min="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sexe du chef</label>
                            <select id="menage_sexe_chef_new" class="form-select">
                                <option value="">-- Sélectionner (optionnel) --</option>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Origine du ménage</label>
                            <select id="menage_origine_id_new" class="form-select">
                                <option value="">-- Sélectionner (optionnel) --</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="menage_sous_quartier_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="createMenage()">
                    <span id="loading-menage" style="display:none;" class="loading-spinner me-2"></span>
                    Créer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Variables pour pré-remplir les selects
const currentQuartierId = {{ $individu->menage->sousQuartier->quartier_id ?? 'null' }};
const currentSousQuartierId = {{ $individu->menage->sous_quartier_id ?? 'null' }};
const currentMenageId = {{ $individu->menage_id ?? 'null' }};

// Preview fichier
function previewFile() {
    const file = document.getElementById('doc_piece').files[0];
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    if (file && file.type.startsWith('image/')) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.className = 'preview-image';
        preview.appendChild(img);
    } else if (file && file.type === 'application/pdf') {
        preview.innerHTML = '<span class="badge bg-info p-2"><i class="fas fa-file-pdf"></i> PDF chargé</span>';
    }
}

// ========== AJAX pour la localisation hiérarchique ==========
async function fetchJson(url) {
    const res = await fetch(url, { 
        headers: { 
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        } 
    });
    if (!res.ok) throw new Error('Erreur de chargement');
    return await res.json();
}

function fillSelect(select, items, placeholder = '-- Sélectionner --', selectedValue = null, valueKey = 'id', textKey = 'nom') {
    select.innerHTML = '';
    const opt = document.createElement('option');
    opt.value = '';
    opt.textContent = placeholder;
    select.appendChild(opt);
    items.forEach(i => {
        const o = document.createElement('option');
        o.value = i[valueKey];
        o.textContent = i[textKey];
        if (selectedValue && i[valueKey] == selectedValue) {
            o.selected = true;
        }
        select.appendChild(o);
    });
    select.disabled = false;
}

function resetDownstream(ids) {
    ids.forEach(id => {
        const el = document.getElementById(id);
        if (el) { 
            el.innerHTML = '<option value="">-- Sélectionner --</option>'; 
            el.disabled = true;
            const btnId = `btn-create-${id.replace('_id', '').replace('quartier', 'quartier').replace('sous_quartier', 'sous-quartier')}`;
            const btn = document.getElementById(btnId);
            if (btn) btn.disabled = true;
        }
    });
}

// ========== Chargement initial avec pré-sélection ==========
document.addEventListener('DOMContentLoaded', async () => {
    const villageId = {{ $user->village_id ?? 'null' }};
    
    if (villageId) {
        // Charger les quartiers du village du point focal
        try {
            const quartiers = await fetchJson(`{{ url('/ajax/villages') }}/${villageId}/quartiers`);
            const quartierSelect = document.getElementById('quartier_id');
            fillSelect(quartierSelect, quartiers, '-- Sélectionner un quartier --', currentQuartierId);
            
            document.getElementById('btn-create-quartier').disabled = false;
            document.getElementById('quartier_village_id').value = villageId;
            
            // Si un quartier est sélectionné, charger les sous-quartiers
            if (currentQuartierId) {
                const sousQuartiers = await fetchJson(`{{ url('/ajax/quartiers') }}/${currentQuartierId}/sous-quartiers`);
                fillSelect(document.getElementById('sous_quartier_id'), sousQuartiers, '-- Sélectionner un sous-quartier --', currentSousQuartierId);
                document.getElementById('btn-create-sous-quartier').disabled = false;
                
                // Si un sous-quartier est sélectionné, charger les ménages
                if (currentSousQuartierId) {
                    const menages = await fetchJson(`{{ url('/ajax/sous-quartiers') }}/${currentSousQuartierId}/menages`);
                    fillSelect(document.getElementById('menage_id'), menages, '-- Sélectionner un ménage --', currentMenageId);
                    document.getElementById('btn-create-menage').disabled = false;
                }
            }
        } catch(e) {
            console.error('Erreur chargement:', e);
        }
    }
    
    // Écouteurs pour la cascade
    document.getElementById('quartier_id').addEventListener('change', async function() {
        const quartierId = this.value;
        resetDownstream(['sous_quartier_id', 'menage_id']);
        
        if (quartierId) {
            try {
                const sousQuartiers = await fetchJson(`{{ url('/ajax/quartiers') }}/${quartierId}/sous-quartiers`);
                fillSelect(document.getElementById('sous_quartier_id'), sousQuartiers, '-- Sélectionner un sous-quartier --');
                document.getElementById('btn-create-sous-quartier').disabled = false;
            } catch(e) {
                console.error('Erreur chargement sous-quartiers:', e);
            }
        }
    });
    
    document.getElementById('sous_quartier_id').addEventListener('change', async function() {
        const sousQuartierId = this.value;
        resetDownstream(['menage_id']);
        
        if (sousQuartierId) {
            try {
                const menages = await fetchJson(`{{ url('/ajax/sous-quartiers') }}/${sousQuartierId}/menages`);
                fillSelect(document.getElementById('menage_id'), menages, '-- Sélectionner un ménage --');
                document.getElementById('btn-create-menage').disabled = false;
            } catch(e) {
                console.error('Erreur chargement ménages:', e);
            }
        }
    });
});

// ========== Fonctions pour créer de nouvelles entités ==========
async function openCreateModal(type) {
    if (type === 'quartier') {
        const villageId = {{ $user->village_id ?? 'null' }};
        if (!villageId) {
            alert('Erreur : village du point focal non défini');
            return;
        }
        document.getElementById('quartier_village_id').value = villageId;
        
        try {
            const types = await fetchJson('{{ route('ajax.types_quartiers') }}');
            const select = document.getElementById('quartier_type_quartier_id_new');
            fillSelect(select, types, '-- Sélectionner (optionnel) --', 'id', 'nom');
        } catch(e) {
            console.error('Erreur chargement types quartiers:', e);
        }
        
        new bootstrap.Modal(document.getElementById('modalCreateQuartier')).show();
    } else if (type === 'sous-quartier') {
        const quartierId = document.getElementById('quartier_id').value;
        if (!quartierId) {
            alert('Veuillez d\'abord sélectionner un quartier');
            return;
        }
        document.getElementById('sous_quartier_quartier_id').value = quartierId;
        
        try {
            const types = await fetchJson('{{ route('ajax.types_sous_quartiers') }}');
            const select = document.getElementById('sous_quartier_type_sous_quartier_id_new');
            fillSelect(select, types, '-- Sélectionner (optionnel) --', 'id', 'nom');
        } catch(e) {
            console.error('Erreur chargement types sous-quartiers:', e);
        }
        
        new bootstrap.Modal(document.getElementById('modalCreateSousQuartier')).show();
    } else if (type === 'menage') {
        const sousQuartierId = document.getElementById('sous_quartier_id').value;
        if (!sousQuartierId) {
            alert('Veuillez d\'abord sélectionner un sous-quartier');
            return;
        }
        document.getElementById('menage_sous_quartier_id').value = sousQuartierId;
        
        try {
            const origines = await fetchJson('{{ route('ajax.origines_menages') }}');
            const select = document.getElementById('menage_origine_id_new');
            fillSelect(select, origines, '-- Sélectionner (optionnel) --', 'id', 'nom');
        } catch(e) {
            console.error('Erreur chargement origines ménages:', e);
        }
        
        new bootstrap.Modal(document.getElementById('modalCreateMenage')).show();
    }
}

async function createQuartier() {
    const nom = document.getElementById('quartier_nom_new').value.trim();
    const villageId = document.getElementById('quartier_village_id').value;
    const historique = document.getElementById('quartier_historique_new').value.trim();
    const typeQuartierId = document.getElementById('quartier_type_quartier_id_new').value;
    
    if (!nom || !villageId) {
        alert('Veuillez remplir tous les champs obligatoires');
        return;
    }

    const loading = document.getElementById('loading-quartier');
    loading.style.display = 'inline-block';

    try {
        const response = await fetch('{{ route('ajax.create.quartiers') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nom: nom,
                village_id: villageId,
                historique: historique || null,
                type_quartier_id: typeQuartierId || null
            })
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Erreur lors de la création');
        }

        const newQuartier = await response.json();
        const quartierSelect = document.getElementById('quartier_id');
        const option = document.createElement('option');
        option.value = newQuartier.id;
        option.textContent = newQuartier.nom;
        option.selected = true;
        quartierSelect.appendChild(option);
        quartierSelect.disabled = false;
        quartierSelect.dispatchEvent(new Event('change'));
        
        bootstrap.Modal.getInstance(document.getElementById('modalCreateQuartier')).hide();
        document.getElementById('formCreateQuartier').reset();
        alert('Quartier créé avec succès !');
    } catch(e) {
        console.error('Erreur création quartier:', e);
        alert('Erreur lors de la création du quartier: ' + e.message);
    } finally {
        loading.style.display = 'none';
    }
}

async function createSousQuartier() {
    const nom = document.getElementById('sous_quartier_nom_new').value.trim();
    const quartierId = document.getElementById('sous_quartier_quartier_id').value;
    const historique = document.getElementById('sous_quartier_historique_new').value.trim();
    const typeSousQuartierId = document.getElementById('sous_quartier_type_sous_quartier_id_new').value;
    
    if (!nom || !quartierId) {
        alert('Veuillez remplir tous les champs obligatoires');
        return;
    }

    const loading = document.getElementById('loading-sous-quartier');
    loading.style.display = 'inline-block';

    try {
        const response = await fetch('{{ route('ajax.create.sous_quartiers') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nom: nom,
                quartier_id: quartierId,
                historique: historique || null,
                type_sous_quartier_id: typeSousQuartierId || null
            })
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Erreur lors de la création');
        }

        const newSousQuartier = await response.json();
        const sqSelect = document.getElementById('sous_quartier_id');
        const option = document.createElement('option');
        option.value = newSousQuartier.id;
        option.textContent = newSousQuartier.nom;
        option.selected = true;
        sqSelect.appendChild(option);
        sqSelect.disabled = false;
        sqSelect.dispatchEvent(new Event('change'));
        
        bootstrap.Modal.getInstance(document.getElementById('modalCreateSousQuartier')).hide();
        document.getElementById('formCreateSousQuartier').reset();
        alert('Sous-quartier créé avec succès !');
    } catch(e) {
        console.error('Erreur création sous-quartier:', e);
        alert('Erreur lors de la création du sous-quartier: ' + e.message);
    } finally {
        loading.style.display = 'none';
    }
}

async function createMenage() {
    const nomChef = document.getElementById('menage_nom_chef_new').value.trim();
    const nbIndividus = document.getElementById('menage_nb_individus_new').value;
    const sexeChef = document.getElementById('menage_sexe_chef_new').value;
    const origineId = document.getElementById('menage_origine_id_new').value;
    const sousQuartierId = document.getElementById('menage_sous_quartier_id').value;
    
    if (!nomChef || !sousQuartierId) {
        alert('Veuillez remplir tous les champs obligatoires');
        return;
    }

    const loading = document.getElementById('loading-menage');
    loading.style.display = 'inline-block';

    try {
        const response = await fetch('{{ route('ajax.create.menages') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nom_chef: nomChef,
                sous_quartier_id: sousQuartierId,
                nb_individus: nbIndividus || null,
                sexe_chef: sexeChef || null,
                origine_id: origineId || null
            })
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Erreur lors de la création');
        }

        const newMenage = await response.json();
        const menageSelect = document.getElementById('menage_id');
        const option = document.createElement('option');
        option.value = newMenage.id;
        option.textContent = newMenage.nom_chef || newMenage.nom;
        option.selected = true;
        menageSelect.appendChild(option);
        menageSelect.disabled = false;
        
        bootstrap.Modal.getInstance(document.getElementById('modalCreateMenage')).hide();
        document.getElementById('formCreateMenage').reset();
        alert('Ménage créé avec succès !');
    } catch(e) {
        console.error('Erreur création ménage:', e);
        alert('Erreur lors de la création du ménage: ' + e.message);
    } finally {
        loading.style.display = 'none';
    }
}
</script>
@endsection

