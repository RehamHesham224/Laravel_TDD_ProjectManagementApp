<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
   <ul>
    <li> {{$project->title}}</li>
    <a href="{{$project->path(). "/edit"}}">Edit Project</a>
    <li>{{$project->description}}</li>
    <li>{{$project->notes ?  $project->notes :"no notes"}}</li>
   </ul>
   <h1>Tasks</h1>
    <ul>
        @foreach ($project->tasks as $task)
         <li> 
            <form action="{{$task->path()}}" method="post">
                @csrf
                @method('PATCH')
                <input type="text" name="body" value="{{$task['body']}}" class="{{$task['completed']? 'text-gray': ''}}">
                <input type="checkbox" name="completed" {{$task['completed']? 'checked': ''}} onchange="this.form.submit()" >
            </form>         
        </li>
        @endforeach
        
        <form action="{{$project->path()."/tasks"}}" method="post">
            @csrf
            <input type="text"  placeholder="Add A Task" name="body">
          </form>
    </ul>
    <h1>General Notes</h1>

    <form action="{{$project->path()}}" method="Post">
        @csrf
        @method('PATCH')
        <textarea type="text"  placeholder="Add A Task" name="notes">{{$project->notes}}</textarea>
        <button type="submit">Save</button>
        @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif
      </form>
      @if ($project->notes)
      <p>{{$project->notes}}</p>
      @endif
   @include('projects.activity')
     

</body>
</html>