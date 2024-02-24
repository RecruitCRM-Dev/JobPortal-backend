<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use App\Models\Job;
class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
        'job_id' => 'required',
        'job_seeker_id' => 'required',
        'status' => 'required' 
    ]);
    $job_id = $validatedData['job_id'];
        $job = Job::find($job_id);
        $job->jobapplication()->create($validatedData);
        return response()->json('The job application has been submitted successfully');
    }

    /**
     * Display the specified resource.
     */
    

   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $job_application =  JobApplication::find($id);
        $job_application->delete();
        return response()->json(['message'=>'Job Application Deleted Successfully!']);
    }
}
