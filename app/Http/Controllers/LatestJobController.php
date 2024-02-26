<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use App\Http\Resources\JobDetailResource;

class LatestJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        $jobs = Job::latest()->limit(7)->get();
        // return response()->json(['jobs' => $jobs], 200);
        return JobDetailResource::collection($jobs);
    } 
    
}
