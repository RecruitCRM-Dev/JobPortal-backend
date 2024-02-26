<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobResource;
use Illuminate\Http\Request;
use App\Models\Scopes\LatestScope;
use App\Models\Job;
use Illuminate\Pagination\Paginator;


class JobController extends Controller
{
    public function index(Request $request){

        $filters = $request->only('search', 'min_salary', 'max_salary', 'experience', 'category');

        $jobs = Job::with('employee')->filter($filters);

        $perPage = $request->query('perPage', 12);
        $currentPage = $request->query('page', 1);

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $paginatedJobs = $jobs->paginate($perPage);

        return response()->json(['jobs' => $paginatedJobs], 200);
    }

    public function show(Request $request, $id)
    {
        $job = Job::find($id);
        if (!$job) {
            return response()->json(['error' => 'Job not found'], 404);
        }
        return new JobResource($job);
    }


}
