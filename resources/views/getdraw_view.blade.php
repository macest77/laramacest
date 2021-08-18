<html>
   
   <head>
      <title>Wyniki Lotto</title>
   </head>
   
   <body>
       <div id="last_draw">
            <p>Ostatnie losowanie</p>
            <p>Data: {{ $result->draw_date }}</p>
            <?php $r = str_replace('|', ', ', substr($result->draw_numbers,1,-1)); ?>
            <p>Numery: {{ $r }}</p>
       </div>
   </body>
</html>
