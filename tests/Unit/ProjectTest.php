<?php

namespace Tests\Unit;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase ;
class ProjectTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_has_a_path()
    {
        $project=Project::factory()->create();
        $this->assertEquals('/projects/'.$project->id, $project->path());
    }
    public function it_can_add_a_task()
    {
        $project=Project::factory()->create();
        $task =$project->addTask('Test Task');
        $this->assertCount(1,$project->tasks);
        $this->assertTrue($project->tasks->contain($task));

        
    }
}