<?php

namespace App\Policies;

use App\Models\ClasseurIndependant;
use App\Models\IndividuIndependant;

class ClasseurIndependantPolicy
{
    public function view(IndividuIndependant $user, ClasseurIndependant $classeur)
    {
        return $user->id === $classeur->individu_independant_id;
    }

    public function create(IndividuIndependant $user)
    {
        return true;
    }

    public function update(IndividuIndependant $user, ClasseurIndependant $classeur)
    {
        return $user->id === $classeur->individu_independant_id;
    }

    public function delete(IndividuIndependant $user, ClasseurIndependant $classeur)
    {
        return $user->id === $classeur->individu_independant_id;
    }
}