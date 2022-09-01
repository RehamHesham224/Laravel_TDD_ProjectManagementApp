<?php

namespace Tests\Feature;

use App\Http\Controllers\ProjectTasksController;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use WithFaker , RefreshDatabase ;
     /** @test */
     public function non_owners_may_not_invite_users()
     {
        $user=User::factory()->create();
        $project=ProjectFactory::create();

         $assertInvitationForbidden=function() use($user, $project){
            $this
         ->actingAs($user)
         ->post($project->path().'/invitation')
         ->assertStatus(403);
         };

         $assertInvitationForbidden($user);

         $project->invite($user);

         $assertInvitationForbidden($user);
     }
    /** @test */
    public function a_project_owner_can_invite_a_user()
    {

        $project=ProjectFactory::create();

        $userToInvite=User::factory()->create();

        $this
        ->actingAs($project->owner)
        ->post($project->path().'/invitation',[
            'email'=>$userToInvite->email
        ])
        ->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($userToInvite));

    }
     /** @test */
     public function the_email_address_must_be_associated_with_a_valid_bird(){

        $project=ProjectFactory::create();

        $this
        ->actingAs($project->owner)
        ->post($project->path().'/invitation',[
            'email'=>'notuser@example.com'
        ])
        ->assertSessionHasErrors([
            'email'=>"The User You are inviting must have an account."
        ],null, 'invitation');
     }
     /** @test */
    public function invited_users_may_update_project_details()
    {
        // $this->withoutExceptionHandling();
        $project=ProjectFactory::create();

        $project->invite($newUser=User::factory()->create());

        $this
            ->actingAs($newUser)
            ->post(action([ProjectTasksController::class,'store'],$project),$task=['body'=>'Task']);

        $this->assertDatabaseHas('tasks',$task);
    }
}
