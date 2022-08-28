<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TriggerActivityTest extends TestCase
{
    use WithFaker , RefreshDatabase ;
    /** @test */
    public function create_a_project()
    {
        $project=Project::factory()->create();
        $this->assertCount(1, $project->activity);
        tap($project->activity->last(),function($activity) {
            $this->assertEquals('created_project',$activity->description);
            $this->assertNull($activity->changes);

        });
    }
     /** @test */
     public function update_a_project()
     {
        $this->withoutExceptionHandling();
         $project=Project::factory()->create();
         $orginalTitle=$project->title;
         $project->update(["title" => "changed"]);
         $this->assertCount(2, $project->activity);
         
         tap($project->activity->last(),function($activity) use ($orginalTitle){
            $this->assertEquals('updated_project',$activity->description);

            $expected=[
                'before'=>['title'=>$orginalTitle],
                'after'=>['title'=>'changed']
            ];
            // $this->assertInstanceOf(Project::class,$activity->subject);
            $this->assertEquals($expected,$activity->changes);

        });
     }

     /** @test */
    public function create_a_new_task()
    {
        $project=Project::factory()->create();
        $project->addTask('some Task');
        $this->assertCount(2, $project->activity);
        tap($project->activity->last(),function($activity){
            // dd($activity);
            $this->assertEquals('created_task',$activity->description);
            $this->assertInstanceOf(Task::class,$activity->subject);
            $this->assertEquals('some Task',$activity->subject->body);

        });
        
    }

    
     /** @test */
     public function completing_a_new_task()
     {
        $project=ProjectFactory::withTasks(1)->create();
         $this->actingAs($project->owner)->patch($project->tasks->first()->path(),[
          'body'=>'footbar',
          'completed'=> true
         ]);
         tap($project->activity->last(),function($activity){
            // dd($activity);
            $this->assertEquals('completed_task',$activity->description);
            $this->assertInstanceOf(Task::class,$activity->subject);
            $this->assertEquals('footbar',$activity->subject->body);

        });
        $this->assertCount(3, $project->activity);
     }
      /** @test */
      public function incompleting_a_new_task()
      {
         $project=ProjectFactory::withTasks(1)->create();
          $this->actingAs($project->owner)->patch($project->tasks->first()->path(),[
            'body'=>'footbar',
            'completed'=> true
           ]);
           $this->actingAs($project->owner)->patch($project->tasks->first()->path(),[
            'body'=>'footbar',
            'completed'=> false
           ]);
        //    dd( $project->fresh()->activity);
        
         $this->assertCount(4, $project->fresh()->activity);
         tap($project->activity->last(),function($activity){
            // dd($activity);
            $this->assertEquals('incompleted_task',$activity->description);
            $this->assertInstanceOf(Task::class,$activity->subject);
            $this->assertEquals('footbar',$activity->subject->body);

        });
      }

       /** @test */
       public function deleting_a_task()
       {
          $project=ProjectFactory::withTasks(1)->create();
          $project->tasks[0]->delete();
          $this->assertCount(3, $project->activity);
       }
}
