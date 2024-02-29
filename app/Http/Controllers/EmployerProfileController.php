<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EmployerProfileRequest;
use App\Http\Resources\EmployerProfileResource;

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
        return new EmployerProfileResource($employer);
    }


    //TODO:Refactor this updateEmployer part
    public function updateEmployer(EmployerProfileRequest $request, string $id) {
        $employer = Employee::find($id);

        if (!$employer) {
            return response()->json(['error' => 'Employer not found'], 404);
        }

        $profilePic = $request->file('profile_pic');

        $data = $request->except(['profile_pic']);

        if ($profilePic) {
            $profilePicPath = $profilePic->store('avatar', 'public');
            $data['profile_pic'] = "http://localhost:8000/storage/$profilePicPath";
        }

        $employer->update($data);

        return new EmployerProfileResource($employer);
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
