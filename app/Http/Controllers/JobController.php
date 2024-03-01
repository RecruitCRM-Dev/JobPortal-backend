<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobCollection;
use App\Http\Resources\JobDetailResource;
use Illuminate\Http\Request;
use App\Models\Scopes\LatestScope;
use App\Models\Job;
use Illuminate\Pagination\Paginator;


class JobController extends Controller
{
    public function index(Request $request){

        $search = $request->input('search');
        $min_salary = $request->input('min_salary');
        $max_salary = $request->input('max_salary');
        $experience = $request->input('experience');
        $category = $request->input('category');
        // $filters = $request->only('search', 'min_salary', 'max_salary', 'experience', 'category');

        $jobs = Job::with('employer')->filter(['search' => $search,
        'min_salary' => $min_salary,
        'max_salary' => $max_salary,
        'experience' => $experience,
        'category' => $category])->paginate(12);

        // $perPage = $request->query('perPage', 12);
        // $currentPage = $request->query('page', 1);

        // Paginator::currentPageResolver(function () use ($currentPage) {
        //     return $currentPage;
        // });

        // $paginatedJobs = $jobs->paginate($perPage);

        // dd($jobs);
        return JobDetailResource::collection($jobs);
        // return response()->json(['jobs' => $paginatedJobs], 200);
    }

    public function show(Request $request, $id)
    {
        $job = Job::with('employer')->find($id);
        if (!$job) {
            return response()->json(['error' => 'Job not found'], 404);
        }
        return new JobDetailResource($job);
    }
}
