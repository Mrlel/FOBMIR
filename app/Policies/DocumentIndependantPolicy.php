<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\IndividuIndependant;

/**
 * Règles d'accès aux documents appartenant au dossier personnel d'un individu
 * indépendant (guard "individu"). Ne concerne pas les documents de ménage
 * (voir MenagePolicy pour le guard "web").
 */
class DocumentIndependantPolicy
{
    public function view(IndividuIndependant $user, Document $document): bool
    {
        return $document->individu_independant_id === $user->id;
    }

    public function create(IndividuIndependant $user): bool
    {
        return true;
    }

    public function update(IndividuIndependant $user, Document $document): bool
    {
        return $this->view($user, $document);
    }

    public function delete(IndividuIndependant $user, Document $document): bool
    {
        return $this->view($user, $document);
    }
}
