<!DOCTYPE html>
<html lang="en">
    <head>
        <title>LARAMACEST @yield('title')</title>
    
        <!-- CSS And JavaScript -->
        <link rel="stylesheet" href="{{ asset('public/css.css') }}" />
        
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>

    <body>
        <header style="width: 50%;margin: auto; text-align: center; font-size: 30px;" ><a href="/">LARAMACEST</a></header>
        @section('sidebar')
            <div id="menu" style="right: 0;width: 25%; text-align: left; position: absolute; top: 0px">Menu<br />
-> <a href="/lotto">Wyniki lotto</a><hr />
-> <a href="/lotto/przewidywania">Przypuszczalne najbliższe wyniki</a><hr />
-> <a href="/reviews">Recenzje albumów</a><hr />
-> <a href="/lista-hard-n-heavy">Lista Hard'n'Heavy</a><br /><br />

Twoje IP:<br />
{{ $_SERVER['REMOTE_ADDR'] }}</div>
        @show
        
        <div class="container" style="width: 75%">
            @yield('content')
        </div>
    </body>
</html>
