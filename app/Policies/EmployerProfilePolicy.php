<?php

namespace App\Policies;

use App\Models\Employer;
use Illuminate\Auth\Access\Response;

class EmployerProfilePolicy
{
    public function show(Employer $employer, Employer $model): bool
    {
        return $employer->id == $model->id;
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(Employer $employer, Employer $model): bool
    {
        return $employer->id == $model->id;
    }
}
