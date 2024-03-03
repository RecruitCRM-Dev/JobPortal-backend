<?php

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;

class UserInsightsTest extends TestCase
{
  protected $token;
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

    public function testGetInsights()
    {
      $user = User::factory()->create();
    
      $response = $this->postJson('/api/user/login',[
        'email' => $user->email, 
        'password' => 'password'
      ]);
      $response->assertStatus(200);
      $this->token = $response->json('data')['token'];
      $header = ['Authorization' => 'Bearer ' . $this->token];
      $response = $this->withHeaders($header)->getJson('api/user/profile/'.$user->id.'/insights');
      $response->assertStatus(200);
    }
    
}
