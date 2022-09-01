@extends('layouts.app')
@section('content')
 <div class="container">
  <ul>
   
    @forelse ($projects as $project)
        <div class="d-flex justify-content-between">  
          <h3><a  class="text-decoration-none text-muted" href="{{$project->path()}}">{{$project['title']}}</a></h3>
          <form action="{{$project->path()}}" method="post">
            @method('DELETE')
            @csrf
            <button class="btn btn-danger" type="submit">Delete</button>
          </form>
        </div>
        <p> {{str_limit($project['description'])}}</p>

      
    @empty
      <li> No Projects Yet.</li>
    @endforelse
   </ul>
 </div>
@endsection