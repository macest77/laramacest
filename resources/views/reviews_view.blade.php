@extends('layouts.app')

@section('title', '- Recenzje albumów')

@section('sidebar')
    @parent

    
@endsection
<style>
  td { padding: 0 5px;}
</style>
@section('content')
       <p>Ostatnie 20 recenzji albumów:</p>
      <table border = 0>
         @foreach ($reviews as $review)
            <?php //$n = str_replace('|',', ',substr($draw->draw_numbers,1,-1) ) ?>
         <tr>
            <td>{{ $review->added }} :</td>
            <td><strong>{{ $review->band_name }}</strong></td>
            <td title="{!! str_replace('\n', '&#10;', $review->rec_comment) !!}"> - <strong>{{ $review->rec_title }}</strong></td>
            <td style="width:40%">{{ $review->description }}</td>
            <td>{{ $review->note }}/10</td>
            <td><a href="{{ $review->link }}" target="_blank">link</a></td>
         </tr>
         @endforeach
      </table>
@endsection
