<?php

namespace App\Policies;

use App\Models\DocumentIndependant;
use App\Models\IndividuIndependant;

class DocumentIndependantPolicy
{
    public function view(IndividuIndependant $user, DocumentIndependant $document)
    {
        return $user->id === $document->individu_independant_id;
    }

    public function create(IndividuIndependant $user)
    {
        return true;
    }

    public function update(IndividuIndependant $user, DocumentIndependant $document)
    {
        return $user->id === $document->individu_independant_id;
    }

    public function delete(IndividuIndependant $user, DocumentIndependant $document)
    {
        return $user->id === $document->individu_independant_id;
    }
}