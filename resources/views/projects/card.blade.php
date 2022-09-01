<div class="card ml-3 mb-4">
    <div class="card-body fw-light">
     <h3>Site Info</h3>
     <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
     @can('manage',$project)
      <form action="{{$project->path()}}" method="post">
          @method('DELETE')
          @csrf
          <button class="btn btn-danger mr-auto" type="submit">Delete</button>
        </form>
      @endcan
</div>
</div>