<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scopes\LatestScope;
use App\Models\Job;

class JobController extends Controller
{
    public function index(Request $request){
        $jobs = Job::latest()->limit(7)->get();
        return response()->json(['jobs'=>$jobs]);
    }    

    public function show(Request $request, $id)
    {
        $job = Job::find($id);
        if (!$job) {
            return response()->json(['error' => 'Job not found'], 404);
        }
        return response()->json(['job' => $job]);
    }

    
}
