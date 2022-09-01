<div class="card mb-4">
<div class="card-body fw-light">

@if($project->activity)
@foreach($project->activity as $activity)

   <div>
    @include("projects.activity.{$activity->description}")
    <span>
        - {{$activity->created_at->diffForHumans(null, true)}}
    </span>
   </div>
@endforeach
@endif

</div>
</div>