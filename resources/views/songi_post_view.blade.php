@extends('layouts.app')

@section('title', '- Lista Hard\'n\'heavy')

@section('sidebar')
    @parent

    
@endsection
<script>
    var votes = {
        freeslots: [10,9,8,7,6,5,4,3,2,1],
        votes: [0,0,0,0,0,0,0,0,0,0],
        checkedvote: 0,
        options: '',
        voted: 0,
        selectedIndex: 0,
        tmpslots: [],
        i: 0,
        vote: function(id) {
            console.log(this.freeslots);
            this.voted = this.checkedvote;
            this.selectedIndex = 10 - this.voted;
            if (this.selectedIndex < 10) {
                this.votes[this.selectedIndex] = 0;
                this.freeslots[this.selectedIndex] = this.voted;
            }
            console.log(this.freeslots);
            this.checkedvote = $("#place"+id).val();
            this.selectedIndex = 10 - this.checkedvote;
            if (this.selectedIndex < 10) {
                this.votes[this.selectedIndex] = id;
                this.freeslots[this.selectedIndex] = 0;
            }
            console.log(this.freeslots);
        },
        showVotes: function(id) {
            this.checkedvote = $("#place"+id).val();
            $("#place"+id).empty();
            if (this.checkedvote > 0) {
                this.options = $('<option></option>').attr("value", this.checkedvote).text(this.checkedvote);
                $("#place"+id).append(this.options);
            }
            this.options = $('<option></option>').attr("value", "0").text("0");
            $("#place"+id).append(this.options);
            $(this.freeslots).each(function(index, element) {
                if (element > 0) {
                    this.options = $('<option></option>').attr("value", element).text(element);
                    $("#place"+id).append(this.options);
                }
            });
            $("#place"+id+" option[value='"+this.checkedvote+"']")[0].selected = true;
            console.log("showVotes "+id+": "+this.checkedvote);
        }
    };
    function checkAndSubmit() {
        var votesCount = 0;
        var EmailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
         
        if (EmailRegex.test($("#temp_songi_list_mail").val()) ) {
            var data = {};
            data["#temp_songi_list_mail"] = $("#temp_songi_list_mail").val();
            datta["_token"] = $("[name='_token']").val();
            $(votes.votes).each(function(index, element) {
                votesCount = votesCount + element;
                data['temp_songi_list_id_'+(index+1)] = element;
            });
            if (votesCount > 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: '/lista-hard-n-heavy',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: ({data}),
                    success:function(response){}
                    
                });
            } else {
                alert('Nie ułożono głosów');
            }
        } else {
            alert('Podaj prawidłowy adres e-mail');
        }
    }
</script>
<style>
  td { padding: 0 5px;}
  span {font-size: 10px; }
</style>
@section('content')
    @foreach ($errors as $error)
        <p>{{ $error }}</p>
    @endforeach
       <p>Najnowsze notowanie:</p>
      <table border = 0>
         @foreach ($songi as $song)
            <?php //$n = str_replace('|',', ',substr($draw->draw_numbers,1,-1) ) ?>
         <tr>
            @if ($song->songi_list_place>100 && $last_place < 101)
                <td colspan="3" style="text-align: center;font-style:italic;"><hr />Propozycje<hr /></td></tr><tr>
             @endif
             <?php $place = (($song->songi_list_place>100)?'P':$song->songi_list_place); ?>
            <td>{{ $place }}.</td>
            <?php $last_place = $song->songi_list_place ?>
            <td><strong>{{ $song->songi_list_title }}</strong> - {{ $song->band_name }} ({{ $song->songi_list_year }})
            @if (!empty($song->youtube) )
            <span>(<a href="https://www.youtube.com/watch?v={{ $song->youtube }}" target="_blank">youtube</a> )</span>
            @endif
            </td>
            <td>przyznaj punkty
            <select id="place{{ $song->songi_list_place }}" onclick="votes.showVotes({{ $song->songi_list_place }})" onchange="votes.vote({{ $song->songi_list_place }})">
                <option value="0" selected="selected">0</option>
            </select></td>
         </tr>
         @endforeach
      </table>
      <br />
      <div id="errors"></div>
      <label for="temp_songi_list_mail">Podaj swój adres e-mail<br /> &nbsp;&nbsp; otrzymasz link do potwierdzenia oddanych głosów</label><br />
      <input type="text" id="temp_songi_list_mail" name="temp_songi_list_mail" />
      <button onclick="checkAndSubmit()">Głosuj</button>
      <form>@csrf</form>
@endsection
