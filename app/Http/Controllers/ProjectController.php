<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    //
    public function index(){
        $projects =auth()->user()->projects;
        // dd($projects);
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
        //validate
       $this->validating();
        //create
        $project=auth()->user()->projects()->create($this->validating());
       
        // Project::create($validating());
        //redirect
        
        return redirect($project->path());
    }
    public function edit(Project $project){
        return view('projects.edit',compact('project'));
    }
    public function update(Project $project){
        // if(auth()->user()->isNot($project->owner)){
        //     abort(403);
        // }
        $this->authorize('update',$project);
          //validate
       $this->validating();
        
        $project->update($this->validating());
        return redirect($project->path());
    }
    protected function validating(){
        return request()->validate([
            'title'=>'required',
            'description'=>'required',
            'notes'=>"min:3"
        ]);
    }
}


