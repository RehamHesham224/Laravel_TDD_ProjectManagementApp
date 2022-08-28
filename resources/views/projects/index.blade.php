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
   
    @forelse ($projects as $project)
        <li><a href="{{$project->path()}}">{{$project['title']}}</a></li>
        <li> {{$project['description']}}</li>

       <form action="{{$project->path()}}" method="post">
        @method('DELETE')
        @csrf
        <button type="submit">Delete</button>
      </form>
    @empty
      <li> No Projects Yet.</li>
    @endforelse
   </ul>
</body>
</html>