<?php

namespace App\Policies;

use App\Models\Classeur;
use App\Models\IndividuIndependant;

/**
 * Règles d'accès aux classeurs appartenant au dossier personnel d'un individu
 * indépendant (guard "individu"). Ne concerne pas les classeurs de ménage
 * (voir MenagePolicy pour le guard "web").
 */
class ClasseurIndependantPolicy
{
    public function view(IndividuIndependant $user, Classeur $classeur): bool
    {
        return $classeur->dossier_id !== null
            && $classeur->dossier?->individu_independant_id === $user->id;
    }

    public function create(IndividuIndependant $user): bool
    {
        return true;
    }

    public function update(IndividuIndependant $user, Classeur $classeur): bool
    {
        return $this->view($user, $classeur);
    }

    public function delete(IndividuIndependant $user, Classeur $classeur): bool
    {
        return $this->view($user, $classeur);
    }
}
