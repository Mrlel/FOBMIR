<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Auth;

trait FiltersByVillage
{
    /**
     * Vérifie si l'utilisateur est admin ou superadmin
     */
    protected function isAdminOrSuperAdmin()
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Récupère le village_id de l'utilisateur connecté
     * Retourne null pour les admins/superadmins
     */
    protected function getUserVillageId()
    {
        if ($this->isAdminOrSuperAdmin()) {
            return null; // Pas de filtre pour les admins
        }
        
        $user = Auth::user();
        return $user->village_id ?? null;
    }

    /**
     * Applique un filtre par village si l'utilisateur n'est pas admin/superadmin
     */
    protected function filterByVillage($query, $villageIdColumn = 'village_id')
    {
        $villageId = $this->getUserVillageId();
        
        if ($villageId !== null) {
            return $query->where($villageIdColumn, $villageId);
        }
        
        return $query; // Pas de filtre pour les admins
    }
}

