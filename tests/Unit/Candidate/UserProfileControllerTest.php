<?php
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;

class UserProfileControllerTest extends TestCase
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
      
      $app = require __DIR__.'/../../../bootstrap/app.php';
      $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
      return $app;
    }

    public function testLogin()
    {
      $users = User::all()->shuffle();
      $this->user = $users->pop();
      $response = $this->postJson('/api/user/login',[
        'email' => $this->user->email, 
        'password' => 'password'
      ]);
      
      $response->assertStatus(200)
      ->assertJsonStructure([
        'data' => [
        ]  
    ]);
    $this->token = $response->json('data')['token'];

    }
    public function testGetProfile()
    {
     $this->testLogin();
      $header = ['Authorization' => 'Bearer ' . $this->token];
      
      $response = $this->withHeaders($header)->getJson('api/user/profile/'.$this->user->id);
      $response->assertStatus(201);
    }
    public function testUpdateProfile()
    {
      $data = [
        "name"=>fake()->name,
        "email"=>"user@example.com",
        "gender"=> "Male",
        "phone"=> "1234567890",
        "address"=> "123 Main St, City, Country",
        "resume"=> "Updated resume",
        "experience"=> 5,
        "profile_pic"=> "http://example.com/profile_pic.jpg",
        "education"=> "Bachelor's Degree",
        "skills"=>["HTML5","Javascript","Vue"]
      ];
      $this->testLogin();
      $header = ['Authorization' => 'Bearer ' . $this->token];
      $response = $this->withHeaders($header)->postJson('api/user/profile/'.$this->user->id);
      $response->assertStatus(201);
    }
    // public function testDeleteProfile()
    // {
    //   $this->testLogin();
    //   $header = ['Authorization' => 'Bearer ' . $this->token];
    //   $response = $this->withHeaders($header)->deleteJson('api/user/profile/'.$this->user->id);
    //   $response->assertStatus(201);
    // }
    
}
