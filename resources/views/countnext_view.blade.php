@extends('layouts.app')

@section('title', '- Lotto - Przewidywania')

@section('sidebar')
    @parent

    
@endsection

@section('content')
       <div id="last_draw">
            <p>Przewidywania najbliższych wyników</p>
            <?php $r = str_replace('|', ', ', substr($next_draw_numbers['next_draw_numbers'],1,-1)); ?>
            <p>Numery: {{ $r }}</p>
       </div>
       <div id="avg_hits">
           <p>Średnia ilość trafień: {{$next_draw_numbers['avg']}} w {{$next_draw_numbers['count']}} losowaniach</p>
       </div>
@endsection
