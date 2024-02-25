<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobSeeker;
use Illuminate\Http\Request;

class MyJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $job_seeker = JobSeeker::find($request['job_seeker_id']);
        $filters = request()->only(
            'search',
            'min_salary',
            'max_salary',
            'experience',
            'category',
            'status'
        );
        //dd($job_seeker);
        $job_applications = $job_seeker->jobapplication()
        ->whereHas('job', function ($query) use ($filters) {
            $query->filter($filters);
        })
        ->when($request['job_application_status'] ?? null, function($query, $status){
            $query->where('status',$status);
        })
        ->with('job')
        ->get();
        return  response()->json(['job_applications'=> $job_applications]);
    }

    /**
    
     * Store a newly created resource in storage.
     */
    
    public function destroy(string $id)
    {
        //
    }
}
