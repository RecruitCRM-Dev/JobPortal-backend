<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use Illuminate\Http\Request;
use App\Http\Resources\EmployerProfileResource;
use Illuminate\Support\Facades\Storage;

class EmployersProfileController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(Employer $employer)
    {
        // $this->authorize('show', $employer);
        return new EmployerProfileResource($employer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employer $employer)
    {

        // $this->authorize('update', $employer);
        $profilePic = $request->file('profile_pic');
        $data = $request->except(['profile_pic']);

        if ($profilePic) {
            //Deleting Old profile picture if already exists
            if ($employer->profile_pic) {
                $oldProfilePicPath = strstr($employer->profile_pic, "/avatar");
                Storage::disk('public')->delete($oldProfilePicPath);
            }

            $profilePicPath = $profilePic->store('avatar', 'public');
            $data['profile_pic'] = "http://localhost:8000/storage/$profilePicPath";
        }

        $employer->update($data);

        return new EmployerProfileResource($employer);
    }
}
