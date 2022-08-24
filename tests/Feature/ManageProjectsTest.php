<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker , RefreshDatabase ;


    /** @test */
    public function guests_cannot_manage_project(){
        $project=Project::factory()->create();

        $this->get('projects')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->get($project->path()."/edit")->assertRedirect('login');
        $this->post('projects',$project->toArray())->assertRedirect('login');

    }


    /** @test */
    public function a_user_can_create_a_project(){
        
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes=[
            'title'=>$this->faker->sentence,
            'description'=>$this->faker->paragraph,
            'notes'=>"General Notes"
        ];
        $response=$this->post('/projects',$attributes);
        $project=Project::where($attributes)->first();
        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects',$attributes);

        // $this->get('/projects')->assertSee($attributes['title']);
        $this->get($project->path())
        ->assertSee($attributes['title'])
        ->assertSee($attributes['description'])
        ->assertSee($attributes['notes']);

    }

     /** @test */
     public function a_user_can_update_a_project(){
        $project =ProjectFactory::create();


        $this->actingAs($project->owner)
            ->patch($project->path(),$attributes=[ 'title'=>"changed",'description'=>"desc","notes"=>"changed"])
            ->assertRedirect($project->path());

        // $this->get($project->path()."/edit")->assertOk();

        $this->assertDatabaseHas('projects', $attributes);


     }
     
     /** @test */
     public function a_user_can_update_a_project_general_notes(){
        $project =ProjectFactory::create();


        $this->actingAs($project->owner)
            ->patch($project->path(),$attributes=["notes"=>"changed"])
            ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);
     }

     /** @test */
     public function a_user_can_view_their_project(){
        $this->withoutExceptionHandling();
        $this->signIn();
        $project=Project::factory()->create(['owner_id'=>auth()->id()]);

        $this->get($project->path())
        ->assertSee($project->title)
        ->assertSee($project->description);
    }
     /** @test */
     public function an_authenticated_user_cannot_view_the_projects_of_others(){
       
        $this->signIn();
        $project=Project::factory()->create();
        $this->get($project->path())->assertStatus(403);

     }
       /** @test */
       public function an_authenticated_user_cannot_update_the_projects_of_others(){
        $this->signIn();
        $project=Project::factory()->create();
        $this->patch($project->path(),[])
        ->assertStatus(403);

     }
    // /** @test */
    // public function a_project_requires_a_title(){
    //     //raw -> create as array
    //     //make -> make but not save
    //     //create -> create as object

    //     $this->signIn();
    //     $attributes=Project::factory()->raw(['title'=>'']);
    //     $this->post('projects',[$attributes])->assertSessionHasErrors('title');
    // }
    //  /** @test */
    //  public function a_project_requires_a_description(){
    //     $this->signIn();
    //     $attributes=Project::factory()->raw(['description'=>'']);
    //     $this->post('projects',[$attributes])->assertSessionHasErrors('description');
    // }

}
