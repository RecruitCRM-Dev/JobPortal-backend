<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \App\Models\JobSeeker::factory(100)->create();
        \App\Models\Employee::factory(30)->create();
        $employees = \App\Models\Employee::all()->shuffle();
        for($i=0;$i<66;$i++)
        {
            \App\Models\Job::factory()->create([
                    'employee_id' => $employees->random()->id
            ]);
            
        }
        $job_seekers = \App\Models\JobSeeker::all()->shuffle();
        for($i=0;$i<100;$i++)
        {
            $job_seeker = $job_seekers->pop();
            $cnt = rand(0,4);
            $jobs = \App\Models\Job::inRandomOrder()->take($cnt)->get();
            for($j=0;$j<$cnt;$j++)
            {
                $job = $jobs->pop();  
                \App\Models\JobApplication::factory()->create([
                    'job_id' => $job->id,
                    'job_seeker_id' => $job_seeker->id
                ]); 
            }

        }

    }
}
