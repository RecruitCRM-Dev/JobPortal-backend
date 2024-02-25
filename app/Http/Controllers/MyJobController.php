<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobApplication;

class MyJobController extends Controller
{
    public function index( Request $request)
    {
        $user_id = $request->input('user_id');

        $filters = $request->only([
            'search',
            'min_salary',
            'max_salary',
            'experience',
            'category',
            'status'
        ]);

        $job_applications = JobApplication::where('user_id', $user_id)
            ->whereHas('job', function ($query) use ($filters) {
                $query->filter($filters);
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->with('job')
            ->get();

        return response()->json(['job_applications' => $job_applications]);
    }

}
