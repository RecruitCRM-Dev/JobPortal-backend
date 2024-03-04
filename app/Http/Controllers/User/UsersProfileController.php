<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserProfileRequest;
use App\Http\Resources\UserProfileResource;

class UsersProfileController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //dd($user);
        return response()->json(['user' => $user], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserProfileRequest $request, User $user)
    {
        // $this->authorize('update', $user);
        $cv = $request->file('resume');
        $profile_pic = $request->file('profile_pic');

        $data = $request->except(['resume', 'profile_pic', 'skills']);

        if($request->has('skills')){
            $data['skills'] = implode(',', $request->input('skills'));
            // dd($data['skills']);
        }

        if ($cv) {
            $cvPath = $cv->store('resume', 'public');
            $data['resume'] = "http://localhost:8000/storage/$cvPath";
        }

        if ($profile_pic) {
            $profilePicPath = $profile_pic->store('avatar', 'public');
            $data['profile_pic'] = "http://localhost:8000/storage/$profilePicPath";
        }

        $user->update($data);

        return response()->json(['user' => $user], 201);
    }
}

