<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;

class LatestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = Job::orderBy('created_at', 'desc')->limit(7)->get();
        return response()->json(['jobs' => $jobs], 200);
    }

    
}
