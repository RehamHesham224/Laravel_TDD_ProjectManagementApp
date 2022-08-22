
@csrf

<div class="row mb-3">
    <label for="title" class="col-md-4 col-form-label text-md-end">Title</label>

    <div class="col-md-6">
        <input id="title" type="text" class="form-control" name="title" autofocus value="{{$project->title}}">
    </div>
</div>
<div class="row mb-3">
    <label for="description" class="col-md-4 col-form-label text-md-end">description</label>

    <div class="col-md-6">
        <input id="description" name="description" type="text" class="form-control" autofocus  value="{{$project->description}}">
    </div>
</div>
<div class="row mb-0">
    <div class="col-md-8 offset-md-4">
        <button type="submit" class="btn btn-primary">
            {{$buttonText}}
        </button>
        <a href="{{$project->path()}}">Cancel</a>
    </div>
</div>
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
                                                
                        
   