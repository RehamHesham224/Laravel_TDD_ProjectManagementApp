   
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create project</div>
    
                    <div class="card-body">
                        <form method="POST" action="/projects">
                            @csrf
    
                            @include('projects._form',[
                                'project'=>new App\Models\Project,
                                'buttonText'=>"Create Project"
                                ])
                           
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    @endsection