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

       $this->followingRedirects()->post('/projects',$attributes=Project::factory()->raw())
        ->assertSee($attributes['title'])
        ->assertSee($attributes['description'])
        ->assertSee($attributes['notes']);

    }
     /** @test */
    function a_user_see_all_project_they_have_been_invited_to_on_their_dashboard(){
        $project=tap(ProjectFactory::create())->invite($this->signIn());
        $this->get('projects')
        ->assertSee($project->title);

    }
      /** @test */
      public function unautherize_users_cannot_delete_a_project(){
        $this->withDeprecationHandling();
        
        $project =ProjectFactory::create();


        $this->delete($project->path())
            ->assertRedirect('/login');

        $user=$this->signIn();

        $this->delete($project->path())
            ->assertStatus(403);


        $project->invite($user);

        $this->actingAs($user)
        ->delete($project->path())
        ->assertStatus(403);
        // $this->assertDatabaseMissing('projects',$project->only('id'));

    }
      /** @test */
      public function a_user_can_delete_a_project(){
        $this->withDeprecationHandling();
        
        $project =ProjectFactory::create();


        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects',$project->only('id'));

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
    /** @test */
    public function a_project_requires_a_title(){
        $this->withoutExceptionHandling();
        $this->signIn();
        $attributes=Project::factory()->raw(['title'=>'']);
        $this->post('projects',[$attributes])->assertSessionHasErrors('title');
    }
    //  /** @test */
    //  public function a_project_requires_a_description(){
    //     $this->signIn();
    //     $attributes=Project::factory()->raw(['description'=>'']);
    //     $this->post('projects',[$attributes])->assertSessionHasErrors('description');
    // }

   
   

}
