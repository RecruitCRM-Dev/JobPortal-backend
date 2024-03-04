<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobCollection;
use App\Http\Resources\JobDetailResource;
use Illuminate\Http\Request;
use App\Models\Scopes\LatestScope;
use App\Models\Job;
use Illuminate\Pagination\Paginator;


class JobController extends Controller
{
    public function index(Request $request)
    {

        $jobType = $request->input('type');
        $category = $request->input('category');
        $salary = $request->input('salary');
        $experience = $request->input('experience');
        // $filters = $request->only('search', 'min_salary', 'max_salary', 'experience', 'category');

        $jobs = Job::with('employer')
            ->where('status', 'Active') 
            ->when($jobType, function ($query) use ($jobType) {
                return $query->whereIn('type', $jobType);
            })
            ->when($salary, function ($query) use ($salary) {
                $query->where(function ($query) use ($salary) {
                    foreach ($salary as $sal) {
                        switch ($sal) {
                            case '2lpa': // 2Lpa
                                $query->orWhere('salary', '<', 200000);
                                break;
                            case '6lpa': // 6Lpa
                                $query->orWhereBetween('salary', [200000, 600000]);
                                break;
                            case '12lpa': // 12Lpa
                                $query->orWhereBetween('salary', [600000, 1200000]);
                                break;
                            case '18lpa': // 18Lpa
                                $query->orWhereBetween('salary', [1200000, 1800000]);
                                break;
                            case '30lpa': // 30Lpa
                                $query->orWhereBetween('salary', [1800000, 3000000]);
                                break;
                            case '40lpa': // 40Lpa
                                $query->orWhere('salary', '>', 4000000);
                                break;
                            // Add more cases as needed
                        }
                    }
                });
                return $query;
            })
            ->when($experience, function ($query) use ($experience) {
                $query->where(function ($query) use ($experience) {
                    foreach ($experience as $exp) {
                        switch ($exp) {
                            case 'entry':
                                $query->orWhere('experience', '<', 2);
                                break;
                            case 'intermediate':
                                $query->orWhereBetween('experience', [2, 15]);
                                break;
                            case 'senior':
                                $query->orWhere('experience', '>', 15);
                                break;
                        }
                    }
                });
                return $query;
            })
            ->when($category, function ($query) use ($category) {
                return $query->whereIn('category', $category);
            })->get();

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
            return response()->json(['error' => 'Job not found..'], 404);
        }
        return new JobDetailResource($job);
    }

    public function getLatestJobs(Request $request)
    {
        $jobs = Job::latest()->where('status', 'Active')->limit(7)->get();
        // return response()->json(['jobs' => $jobs], 200);
        return JobDetailResource::collection($jobs);
    }
}
