@extends('layouts.app')

@section('title', '- Lista Hard\'n\'heavy')

@section('sidebar')
    @parent

    
@endsection

@section('content')
    <style>
        label {width: 45px;
            display: inline-block;
            line-height: 25px;}
        input {border-radius: 20px;
            border: 0px;
            height: 20px;}
        .info {font-weight:bold;}
    </style>
    @if (count($save_infos) > 0 )
        @foreach($save_infos as $info)
    <p><strong>{{ $info }}</strong></p>
        @endforeach
    @endif
    <p>Dodaj propozycję do notowania</p>

    <form method="post">@csrf
        <label for="songi_list_title">tytuł:</label>
        <input type="text" id="songi_list_title" name="songi_list_title" /><br />
        <label for="songi_list_band_id">zespół:</label>
        <select id="songi_list_band_id" name="songi_list_band_id"><option value="0">--- wybierz zespół ---</option>
            @foreach($bands as $band)
                <option value="{{ $band->band_id}}">{{ $band->band_name }}</option>
            @endforeach
        </select><br />
        <label for="songi_list_year">rok:</label>
        <input type="text" id="songi_list_year" name="songi_list_year" /><br />
        <label for="admin_password">hasło:</label>
        <input type="password" id="admin_password" name="admin_password" />
        <input type="submit" value="Zatwierdź" style="padding: 0 20px" />
    </form>
@endsection
