
@if ($errors->{$bag ?? 'default'}->any())
<div class="text-danger">
    <ul>
        @foreach ($errors->{$bag ?? 'default'}->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif