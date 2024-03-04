<?php
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;

class UserAuthControllerTest extends TestCase
{
  protected static ?string $password;
  protected $token;
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
      
      $app = require __DIR__. '/../../../bootstrap/app.php';
      $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
      return $app;
    }

    public function testUserLogin()
    {
      $user = User::factory()->create();
      $response = $this->postJson('/api/user/login',[
        'email' => $user->email, 
        'password' => 'password'
      ]);
      
      $response->assertStatus(200)
      ->assertJsonStructure([
        'data' => [
        ]  
    ]);
    $this->token = $response->json('data')['token'];

    }
    public function testUserRegister()
    {
      $data = [
        'name' => fake()->name(),
        'contact' => fake()->phoneNumber(),
        'email' => fake()->unique()->safeEmail(),
        'password' => "password",
        'password_confirmation' => 'password'
      ];
      $response = $this->postJson('api/user/register', $data);
      $response->assertStatus(201);
    }
     public function testUserLogout()
     {
        
        $this->testUserLogin();
        $header = ['Authorization' => 'Bearer ' . $this->token];
        $response = $this->withHeaders($header)->postJson('api/user/logout');
        $response->assertStatus(200);

     }
    public function  testUserAuthorization()
    {
      $this->testUserLogin();
      $header = ['Authorization' => 'Bearer ' . $this->token];
      $response = $this->withHeaders($header)->getJson('api/user');
      $response->assertStatus(200);

    }
    
}
