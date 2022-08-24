<?php

namespace Tests\Feature;

use App\Models\Project;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivityFeedTest extends TestCase
{
    use WithFaker , RefreshDatabase ;
    /** @test */
    public function create_a_project_records_activity()
    {
        $project=Project::factory()->create();
        $this->assertCount(1, $project->activity);
        $this->assertEquals('created',$project->activity[0]->description);
    }
     /** @test */
     public function update_a_project_records_activity()
     {
         $project=Project::factory()->create();
         $project->update(["title" => "changed"]);
         $this->assertCount(2, $project->activity);
         $this->assertEquals('updated',$project->activity->last()->description);
     }

     /** @test */
    public function create_a_new_task__records_activity()
    {
        $project=Project::factory()->create();
        $project->addTask('some Task');
        $this->assertCount(2, $project->activity);
        $this->assertEquals('Created Task',$project->activity->last()->description);
    }

    
     /** @test */
     public function completing_a_task__records_activity()
     {
        $project=ProjectFactory::withTasks(1)->create();
         $this->actingAs($project->owner)->patch($project->tasks->first()->path(),[
          'body'=>'footbar',
          'completed'=> true
         ]);
        $this->assertCount(3, $project->activity);
         $this->assertEquals('Completed Task',$project->activity->last()->description);
     }
}
