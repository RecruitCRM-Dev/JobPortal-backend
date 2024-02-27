<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserProfileController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json(['error'=>'User not found'],404);
        }
        return response()->json(['user'=>$user]);
    }

    //TODO:Refactor this updateUser part 
    public function updateUser(UserProfileRequest $request, string $id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $cv = $request->file('resume');
        $avatar = $request->file('avatar');

        $data = $request->except(['resume', 'avatar']);

        if ($cv) {
            $cvPath = $cv->store('resume', 'public');
            $data['resume'] = "http://localhost:8000/storage/$cvPath";
        }

        if ($avatar) {
            $avatarPath = $avatar->store('avatar', 'public');
            $data['profile_pic'] = "http://localhost:8000/storage/$avatarPath";
        }

        $user->update($data);
         
        return response()->json(['user' => $user], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserProfileRequest $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $file = $request->file('resume');
        $user->update($request->all());

        return response()->json(['user' => $file], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json(['User not found'],404);
        }
        Auth::logout();
        $user->delete();
        return response()->json(['User deleted'],201);     
    }
}
