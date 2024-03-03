<?php

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;

class AuthControllerTest extends TestCase
{
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

    public function testLogin()
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

    }
    
}
