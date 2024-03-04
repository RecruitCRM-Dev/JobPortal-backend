<?php

use App\Models\Employer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;

class EmployerAuthControllerTest extends TestCase
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

    public function testEmployerLogin()
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
    public function testEmployerRegister()
    {
      $data = [
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'password' => "password",
        'password_confirmation' => 'password'
      ];
      $response = $this->postJson('api/employer/register', $data);
      $response->assertStatus(201);
    }
     public function testEmployerLogout()
     {
        
        $this->testEmployerLogin();
        $header = ['Authorization' => 'Bearer ' . $this->token];
        $response = $this->withHeaders($header)->postJson('api/employer/logout');
        $response->assertStatus(200);

     }
    // public function  testUserAuthorization()
    // {
    //   $this->token = $this->testLogin();
    //   $header = ['Authorization' => 'Bearer ' . $this->token];
    //   $response = $this->withHeaders($header)->getJson('api/user');
    //   $response->assertStatus(200);

    // }
    
}
