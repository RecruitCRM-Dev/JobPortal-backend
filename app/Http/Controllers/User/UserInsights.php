<?php

namespace App\Http\Controllers\User;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Http\Controllers\Controller;

class UserInsights extends Controller
{
    public function getInsights(User $user) {

        $id = $user->id;
        $jobss = JobApplication::with('job:id,category,type')->where('user_id', $id)
            ->get();

        return response()->json($jobss);
 
        foreach (JobApplication::$status as $job_status) {
            $statusCount[$job_status] = 0;
        }
        
        foreach (Job::$category as $job_category) {
            $categoryCount[$job_category] = 0;
        }
        
        foreach (Job::$jobType as $job_type) {
            $typeCount[$job_type] = 0;
        }

        foreach ($jobss as $job) {
            // Count by Status
            $status = $job->status;
            $statusCount[$status] = isset($statusCount[$status]) ? $statusCount[$status] + 1 : 1;
        
            // Count by Category
            $category = $job->job->category;
            $categoryCount[$category] = isset($categoryCount[$category]) ? $categoryCount[$category] + 1 : 1;
        
            // Count by Type
            $type = $job->job->type;
            $typeCount[$type] = isset($typeCount[$type]) ? $typeCount[$type] + 1 : 1;
        }    

        // $result = ['status' => $statusCount];
        return response()->json(['status' => $statusCount, 'category' => $categoryCount, 'type' => $typeCount]);
    }
}
