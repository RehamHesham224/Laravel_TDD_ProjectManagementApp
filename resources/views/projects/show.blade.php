@extends('layouts.app')
@section('content')
   
<div class="container">
    <div class="d-flex justify-content-between items-center">
        <h2> {{$project->title}}</h2>
        <div>
            @foreach($project->members as $member)
                <img class="rounded-circle mr-2" style="width: 3rem;" src="{{gravatar_url($member->email)}}" alt="{{$member->name}}'s avater">
            @endforeach
            <img class="rounded-circle mr-2" style="width: 3rem;" src="{{gravatar_url($project->owner->email)}}" alt="{{$project->owner->name}}'s avater">
            <a class="btn btn-secondary bt-2" href="{{$project->path(). "/edit"}}">Edit Project</a>
        </div>
    </div>
        
        {{-- <li> {{$project->notes ? "Notes : ".  $project->notes :"no notes"}}</li> --}}
      
        <div class="d-flex  justify-content-between ">
            <div class="flex-grow-1 pr-2">
                <p>{{$project->description}}</p>
                <h3>Tasks</h3>
                @foreach ($project->tasks as $task) 
                    <form action="{{$task->path()}}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="d-flex mb-3 input-group">
                            <input type="text" name="body" value="{{$task['body']}}" class="{{$task['completed']?'text-muted': ''}} form-control">
                            <div class="input-group-text">
                                <input class="form-check-input"  type="checkbox" name="completed" {{$task['completed']? 'checked': ''}} onchange="this.form.submit()" >
                            </div>
                        </div>
                    </form>         
                @endforeach
                
                <form action="{{$project->path()."/tasks"}}" method="post">
                    @csrf
                    <input class="form-control mb-2"  type="text"  placeholder="Add A Task" name="body">
                  </form>
                  <hr>
            <h3>General Notes</h3>
        
            <form action="{{$project->path()}}" method="Post">
                @csrf
                @method('PATCH')
                <textarea class="form-control mb-2"   type="text"  placeholder="Add A Task" name="notes">{{$project->notes}}</textarea>
                <button class="btn btn-primary" type="submit">Save</button>
                @include('projects.errors')
            </form>
            </div>
            <div >
           
            {{-- @if ($project->notes)
                <p>{{$project->notes}}</p>
            @endif --}}
            
                @include('projects.card')   
                @include('projects.activity') 
                
                @can('manage',$project)
                @include('projects.invite')
                @endcan
               
         </div>
        </div>
        
</div>
     
@endsection