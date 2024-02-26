<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Http\Resources\JobDetailResource;
use App\Http\Resources\JobApplicationResource;

class EmployerPostedJobsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Employee $employer)
    {
        $jobs = $employer->jobs()->paginate(10);
        return JobDetailResource::collection($jobs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employer, Job $job)
    {   
        $users = JobApplication::with(['user', 'job'])
        ->whereHas('job', function ($query) use ($job) {
            $query->where('id', $job->id);
        })
        ->get();
       
        return JobApplicationResource::collection($users);
        // return response()->json(['job' => $users]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
