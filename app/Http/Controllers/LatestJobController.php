<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
class LatestJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        $jobs = Job::latest()->limit(7)->get();
        return response()->json(['jobs' => $jobs], 200);
    } 
    
}
