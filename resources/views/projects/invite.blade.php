<div class="card ml-3">
    <div class="card-body fw-light">
        <form action="{{$project->path()."/invitation"}}" method="post">
            @csrf
            <div class="mb-3">
                <input type="text" name="email"  class="form-control mb-4">
                <button class="btn btn-sm btn-secondary">Invite</button>
                   @include('projects.errors',['bag' =>'invitation'])
            </div>
        </form>  
    </div>
</div>