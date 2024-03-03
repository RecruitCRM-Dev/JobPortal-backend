<?php

use Illuminate\Foundation\Testing\TestCase;

class JobControllerTest extends TestCase
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

    public function testGetLatestJobs()
    {
      $response = $this->get('/api/jobs/latest');
      
      $response->assertStatus(200)
      ->assertJsonStructure([
        'data' => [
        ]
    ]);

    }
    public function testIndex()
    {
      $response = $this->get('/api/jobs');
      
      $response->assertStatus(200)
      ->assertJsonStructure([
        'data' => []
      ]);
    }
    public function testShow()
    {
      $response = $this->get('/api/jobs/1');
      
      $response->assertStatus(200)
      ->assertJsonStructure([
        'data' => []
      ]);
    }
}
