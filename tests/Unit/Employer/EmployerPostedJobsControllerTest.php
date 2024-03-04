<?php

use App\Models\Employer;
use App\Models\Job;
use Illuminate\Foundation\Testing\TestCase;

class EmployerPostedJobsControllerTest extends TestCase
{
  protected static ?string $password;
  protected $token;
  protected $employer;
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
      
      $app = require __DIR__.'/../../../bootstrap/app.php';
      $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
      return $app;
    }

    public function testLogin()
    {
      $employers = Employer::all()->shuffle();
      $this->employer = $employers->pop();
      $response = $this->postJson('/api/employer/login',[
        'email' => $this->employer->email, 
        'password' => 'password'
      ]);
      
      $response->assertStatus(200)
      ->assertJsonStructure([
        'data' => [
        ]  
    ]);
    $this->token = $response->json('data')['token'];

    }
    public function testGetCandiatesAppliedToEmployerPostedJob()
    {
     $this->testLogin();
      $header = ['Authorization' => 'Bearer ' . $this->token];
      $jobs = Job::where('employer_id',$this->employer->id)->get();
      $job = $jobs->pop();
      $response = $this->withHeaders($header)->getJson('/api/employer/'.$this->employer->id.'/jobs/'.$job->id);
      $response->assertStatus(200);
    }
    public function testGetEmployerPostedJobs()
    {
      $this->testLogin();
      $header = ['Authorization' => 'Bearer ' . $this->token];
      $response = $this->withHeaders($header)->getJson('api/employer/'.$this->employer->id.'/jobs');
      $response->assertStatus(200);
    }
    public function testEmployerPostAJob()
    {
      $data = [
        "title"=> "Product Manager 2",
    "description"=> "Exciting opportunity for a skilled product manager.",
    "responsibilities"=> "Lead product development and oversee project timelines.",
    "category"=> "IT",
    "experience"=> 5,
    "salary"=> 75000,
    "location"=> "San Francisco",
    "type"=> "Internship"
      ];
      $this->testLogin();
      $header = ['Authorization' => 'Bearer ' . $this->token];
      $response = $this->withHeaders($header)->postJson('api/employer/'.$this->employer->id.'/jobs',$data);
      $response->assertStatus(200);
    }
    
    
}
