<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvetationRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectInvitationController extends Controller
{
    //
    public function store(Project $project, ProjectInvetationRequest $request){

        $user=User::whereEmail(request('email'))->first();
        $project->invite($user);

        return redirect($project->path());

    }
}
