@extends('layouts.app')

@section('title', '- Lista Hard\'n\'heavy')

@section('sidebar')
    @parent

    
@endsection
<script>
    
</script>
<style>
    .w30 {width: 30px; }
    #listing div {float:left; }
</style>
@section('content')
    @if (count($standing) > 0)
        <p>Notowanie {{ $standing['id'] }} z dnia {{ $standing['stand_date'] }}:</p>
        <div id="listing">
        @for($i=0; $i < count($standing['standing_data']);$i++)
        <div class="w30">{{ $i+1 }}.</div>
        <div>{{ $standing['standing_data'][$i+1]['name'] }} {{ $standing['standing_data'][$i+1]['previous'] }}</div>
        <div style="clear:both"></div>
        @endfor
        </div>
    @else
        <p>Brak danych</p>
    @endif
@endsection
