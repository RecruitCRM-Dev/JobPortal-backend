<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Http\Request;
use App\Http\Resources\EmployerProfileResource;

class EmployersProfileController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(Employer $employer)
    {
        return new EmployerProfileResource($employer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employer $employer)
    {

        $this->authorize('update', $employer);
        $avatar = $request->file('avatar');
        $data = $request->except(['avatar']);

        if ($avatar) {
            $avatarPath = $avatar->store('avatar', 'public');
            $data['profile_pic'] = "http://localhost:8000/storage/$avatarPath";
        }

        $employer->update($data);

        return new EmployerProfileResource($employer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employer $employer)
    {
        //
    }
}
