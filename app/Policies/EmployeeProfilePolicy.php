<?php

namespace App\Policies;

use App\Models\Employee;
use Illuminate\Auth\Access\Response;

class EmployeeProfilePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(Employee $employee, Employee $model): bool
    {
        return $employee->id == $model->id;
    }
}
