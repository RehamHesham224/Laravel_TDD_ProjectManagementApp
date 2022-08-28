You Upadate A Project 
@if(count($activity->changes['after']) ==1 )
    <span>" {{key($activity->changes['after'])}}"</span>
@endif