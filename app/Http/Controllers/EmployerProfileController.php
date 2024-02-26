<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\EmployerResource;
use App\Http\Requests\EmployerProfileRequest;

class EmployerProfileController extends Controller
{
     /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employer = Employee::find($id);
        if(!$employer){
            return response()->json(['error'=>'User not found'],404);
        }
        return new EmployerResource($employer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployerProfileRequest $request, string $id)
    {
        
        $employer = Employee::find($id);

        if (!$employer) {
            return response()->json(['error' => 'Employer not found'], 404);
        }
        
        $employer->update($request->all());
        return response()->json(['user' => $employer], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Employee::find($id);
        if(!$user){
            return response()->json(['Employer not found'],404);
        }
        Auth::logout();
        $user->delete();
        return response()->json(['Employer deleted'],201);     
    }
}
