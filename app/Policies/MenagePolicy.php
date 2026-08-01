<?php

namespace App\Policies;

use App\Models\Menage;
use App\Models\User;

/**
 * Règle d'accès unique à un ménage (et à ses pochettes/dossiers/classeurs/documents) :
 * - admin / superadmin : accès total
 * - point_focal : uniquement les ménages de son propre village
 *
 * Remplace la logique canAccessMenage()/canManageMenage() qui était dupliquée à
 * l'identique dans ClasseurController, DossierController, PochetteController et
 * MenageDocumentController.
 */
class MenagePolicy
{
    public function view(User $user, Menage $menage): bool
    {
        return $this->hasAccess($user, $menage);
    }

    public function update(User $user, Menage $menage): bool
    {
        return $this->hasAccess($user, $menage);
    }

    public function delete(User $user, Menage $menage): bool
    {
        return $this->hasAccess($user, $menage);
    }

    private function hasAccess(User $user, Menage $menage): bool
    {
        if (in_array($user->role, ['admin', 'superadmin'], true)) {
            return true;
        }

        if ($user->role === 'point_focal' && $user->village_id) {
            $menage->loadMissing('sousQuartier.quartier');
            return $menage->sousQuartier?->quartier?->village_id === $user->village_id;
        }

        return false;
    }
}
