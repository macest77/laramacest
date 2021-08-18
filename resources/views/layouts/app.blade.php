<!DOCTYPE html>
<html lang="en">
    <head>
        <title>LARAMACEST @yield('title')</title>

        <!-- CSS And JavaScript -->
    </head>

    <body>
        <header style="width: 50%;margin: auto; text-align: center; font-size: 30px;" ><a href="/">LARAMACEST</a></header>
        @section('sidebar')
            <div id="menu" style="right: 15px; text-align: left; position: absolute; top: 0px">Menu<br />
-> <a href="/lotto">Wyniki lotto</a><br />
-> <a href="/lotto/przewidywania">Przypuszczalne najbliższe wyniki</a></div>
        @show
        
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
