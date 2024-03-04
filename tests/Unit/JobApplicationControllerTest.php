<?php

use App\Models\Employer;
use App\Models\Job;
use Illuminate\Foundation\Testing\TestCase;
use App\Models\User;
class JobApplicationControllerTest extends TestCase
{
  protected static ?string $password;
  protected $token;
  protected $user;
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
      
      $app = require __DIR__.'/../../bootstrap/app.php';
      $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
      return $app;
    }
    public function testLogin(){
      $users = User::all()->shuffle();
      $this->user = $users->pop();
      $response = $this->postJson('/api/user/login',[
        'email' => $this->user->email, 
        'password' => 'password'
      ]);
      $response->assertStatus(200);
      $this->token = $response->json('data')['token'];
    }
    public function testIndex()
    {
      $this->testLogin();
      $header = ['Authorization' => 'Bearer ' . $this->token];
      $response = $this->withHeaders($header)->getJson('api/user/'.$this->user->id.'/jobs');
      $response->assertStatus(200);
    }
    public function testJobApplied()
    {
      $jobs = Job::all()->shuffle();
      $job = $jobs->pop();
      $this->testLogin();
      $header = ['Authorization' => 'Bearer ' . $this->token];
      $response = $this->withHeaders($header)->getJson('api/user/'.$this->user->id.'/jobs/'.$job->id);
      $response->assertStatus(200);
    }
    public function testApplyJob()
    {
      $this->testLogin();
      $employers= Employer::all()->shuffle();
      $employer = $employers->pop();
      $job = Job::factory()->create([
        'employer_id' => $employer->id
      ]);
      $data = [
        'job_id'=>$job->id,
        'user_id'=>$this->user->id,
        'status' => 'Just_Applied'
      ];
      $header = ['Authorization' => 'Bearer ' . $this->token];
      $response = $this->withHeaders($header)->postJson('api/user/'.$this->user->id.'/jobs',$data);
      $response->assertStatus(200);
    }
    
    
}
