<?php
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Employer;
use Illuminate\Foundation\Testing\TestCase;

class EmployerProfileControllerTest extends TestCase
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
    public function testGetEmployerProfile()
    {
     $this->testLogin();
      $header = ['Authorization' => 'Bearer ' . $this->token];
      
      $response = $this->withHeaders($header)->getJson('api/employer/profile/'.$this->employer->id);
      $response->assertStatus(200);
    }
    public function testUpdateEmployerProfile()
    {
      $data = [
        "name"=>fake()->name,
        "description" => fake()->sentence
      ];
      $this->testLogin();
      $header = ['Authorization' => 'Bearer ' . $this->token];
      $response = $this->withHeaders($header)->postJson('api/employer/profile/'.$this->employer->id);
      $response->assertStatus(200);
    }
    // public function testDeleteProfile()
    // {
    //   $this->testLogin();
    //   $header = ['Authorization' => 'Bearer ' . $this->token];
    //   $response = $this->withHeaders($header)->deleteJson('api/user/profile/'.$this->user->id);
    //   $response->assertStatus(201);
    // }
    
}
