<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    //
    public function index(){
        // $projects =auth()->user()->projects;
        // dd($projects);
        $projects =auth()->user()->accessibleProjects();
        return view('projects.index',compact('projects'));
    }
    public function show(Project $project){
        // $project =Project::findOrFail(request('project'));
        $this->authorize('update',$project);
        return view('projects.show',compact('project'));
    }
    public function create(){
        return view('projects.create');
    }
    public function store(){
        $this->validating();
        $project=auth()->user()->projects()->create($this->validating());
        if($tasks=request('tasks')){
            $project->addTasks($tasks);
        }
        return redirect($project->path());
    }
    public function edit(Project $project){
        return view('projects.edit',compact('project'));
    }

    public function update(UpdateProjectRequest $request){
        return redirect($request->save()->path());
    }
    public function destroy(Project $project){
        $this->authorize('manage',$project);
        $project->delete();
        return redirect('/projects');
    }

    protected function validating(){
        return request()->validate([
            'title'=>'sometimes|required',
            'description'=>'sometimes|required',
            'notes'=>"nullable"
        ]);
    }
}


