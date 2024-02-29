<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Resources\EmployerProfileResource;

class EmployeesProfileController extends Controller
{
    
    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {   
        return new EmployerProfileResource($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {

        $this->authorize('update', $employee);
        $avatar = $request->file('avatar');
        $data = $request->except(['avatar']);

        if ($avatar) {
            $avatarPath = $avatar->store('avatar', 'public');
            $data['profile_pic'] = "http://localhost:8000/storage/$avatarPath";
        }

        $employee->update($data);
         
        return new EmployerProfileResource($employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
