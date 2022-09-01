@endsection
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit project</div>
    
                    <div class="card-body">
                        <form method="POST" action="{{$project->path()}}">
                            @method('PATCH')
                                @include('projects._form',[
                                    'buttonText'=>"Update Project"
                                ])
                                
                              
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection