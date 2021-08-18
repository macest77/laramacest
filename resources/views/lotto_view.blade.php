@extends('layouts.app')

@section('title', '- Wyniki Lotto')

@section('sidebar')
    @parent

    
@endsection
<style>
  td { padding: 0 5px;}
</style>
@section('content')
       <p>LOTTO - Wyniki ostatnich 10 losowań</p>
      <table border = 0>
         <tr>
            <td>Data losowania</td>
            <td>Wylosowane numery</td>
         </tr>
         @foreach ($draws as $draw)
            <?php $n = str_replace('|',', ',substr($draw->draw_numbers,1,-1) ) ?>
         <tr>
            <td>{{ $draw->draw_date }} :</td>
            <td>{{ $n }}</td>
         </tr>
         @endforeach
      </table>
@endsection
