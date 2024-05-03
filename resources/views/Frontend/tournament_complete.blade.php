@extends('Frontend.templates.layout')
@extends('Frontend.templates.nav')
@extends('Frontend.templates.footer')

@section('Title','Tournament Results')
@section('Schedule','active')

@section('HeadContent')
<script>
    $(document).ready(function () {
        var slider = tns({
            container: '#sliderGameFinal',
            controls: false,
            nav: true,
            navPosition: "bottom",
            mouseDrag: true,
            autoplay: true,
            autoplayButtonOutput: false,
            rewind: false,
            gutter: 16,
            responsive: {
                0: {
                    items: 1.3
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                },
                1200: {
                    items: 4
                }
            }
        });

        // $('.event-select').empty();
        // $('.slider-bracket').empty();
        var event_id = $('input[name=event_id]').val();
        var response = $('input[name=response]').val();
        var url = $('input[name=results_url]').val();
        // var data = JSON.parse(response);

        // console.log(data);
        // $.each(data, function(key, value){

        //     var option1 = 
        //         `<option value="${key}">${value.name}</option>`;
                
        //     var option2 = 
        //         ``;

        //     var option3 = 
        //         ``;

        //     // 上方勝利組-start
        //     $.each(value.rankings, function(key2, value2){
        //         if(value2.rank == 1){
        //             option2 += 
        //                 `<div class="avatar avatar-${key}" style="display: none;">
        //                     <div class="img-by img-by-1by1">
        //                         <img src="/images/avatar-01.svg" alt="title">
        //                     </div>
        //                 </div>
        //                 <div class="tournamentWinner-title tournamentWinner-title-${key}" style="display: none;">${value2.user_name}</div>`;
        //             option2 += 
        //                 `<div class="tournamentWinner-list tournamentWinner-list-${key}" style="display: none;">`;
        //         }
        //         if(value2.rank == 1){
        //             option2 += 
        //                 `<div class="item winner">
        //                     <div class="avatar">
        //                         <div class="img-by img-by-1by1">
        //                             <img src="/images/avatar-01.svg" alt="title">
        //                         </div>
        //                     </div>
        //                     <div class="item-title">
        //                         <div class="item-title-inner">
        //                             ${value2.rank}<strong>${value2.user_name}</strong>
        //                         </div>
        //                     </div>
        //                 </div>`;
        //         }
        //         if(value2.rank == 2){
        //             option2 += 
        //                 `<div class="item loser">
        //                     <div class="avatar">
        //                         <div class="img-by img-by-1by1">
        //                             <img src="/images/avatar-01.svg" alt="title">
        //                         </div>
        //                     </div>
        //                     <div class="item-title">
        //                         <div class="item-title-inner">
        //                             ${value2.rank}<strong>${value2.user_name}</strong>
        //                         </div>
        //                     </div>
        //                 </div>`;
        //         } 
        //     })
        //     option2 += 
        //         `</div>`;
        //     // 上方勝利組-end

        //     // 排名列表-start
        //     var total = value.rankings.length;
        //     var half = Math.ceil(total/2);
        //     var remainder = (total-half);
        //     // 左邊
        //     option3 += 
        //         `<table class="table table-borderless table-list1-${key}" style="display: none;">
        //             <thead>
        //                 <tr>
        //                     <th scope="col">Place</th>
        //                     <th scope="col">Team Name</th>
        //                 </tr>
        //             </thead>
        //             <tbody>`;
        //         $.each(value.rankings, function(key3, value3){
        //             if((key3+1) <= half){
        //                 option3 += 
        //                     `<tr>
        //                         <th scope="row">${value3.rank}</th>
        //                         <td>${value3.user_name}</td>
        //                     </tr>`;
        //             }
        //         })
        //     option3 +=
        //         `   </tbody>
        //         </table>`;
        //     // 右邊
        //     option3 += 
        //         `<table class="table table-borderless table-list2-${key}" style="display: none;">
        //             <thead>
        //                 <tr>
        //                     <th scope="col">Place</th>
        //                     <th scope="col">Team Name</th>
        //                 </tr>
        //             </thead>
        //             <tbody>`;
        //         $.each(value.rankings, function(key4, value4){
        //             if(key4 >= half){
        //                 option3 += 
        //                     `<tr>
        //                         <th scope="row">${value4.rank}</th>
        //                         <td>${value4.user_name}</td>
        //                     </tr>`;
        //             }
        //         })
        //     option3 +=
        //         `   </tbody>
        //         </table>`;
        //     // 排名列表-end

        //     $('input[name=key]').val(0);
        //     $('.bracket-select').append(option1);
        //     $('.tournamentWinner-top').append(option2);
        //     $('.avatar-0').show();
        //     $('.tournamentWinner-title-0').show();
        //     $('.tournamentWinner-list-0').show();
        //     $('.tournamentrank-list').append(option3);
        //     $('.table-list1-0').show();
        //     $('.table-list2-0').show();
        // })

        $.ajax({
            type: 'get',
            url: url,
            data: {
                'event_id' : event_id,
            },
            success: function(e){
                e = JSON.parse(e);
                console.log(e);

                $('.selectSp2').empty();
                $('.event-select').empty();
                $('.slider-bracket').empty();
                let new_count = 0;
                var status = 'Progress';

                $.each(e.brackets, function(okey, ovalue){
                    var count_rounds = ovalue.rounds.length;
                    var number_of_brackets = e.number_of_brackets;
                    
                    if(Math.pow(2, count_rounds-1) == number_of_brackets){
                        var status = 'End';
                        console.log(status);

                        var end_data = ovalue.rounds.slice(-2);
                        var end_data2 = end_data.shift();

                        var team_id_1 = 0;
                        var score_1 = 0;
                        var team_id_2 = 0;
                        var score_2 = 0;

                        $.each(end_data2.branchs, function(akey, avalue){
                            $.each(avalue, function(akey2, avalue2){

                                if(akey2 == 0){
                                    team_id_1 = avalue2.team_id;
                                    score_1 = avalue2.current_score;
                                }else{
                                    team_id_2 = avalue2.team_id;
                                    score_2 = avalue2.current_score;
                                }
                            })
                        })

                        if((score_1 > score_2)||(score_1 == score_2)){
                            getTeamName(team_id_1);
                            var champion_id = team_id_1;
                            var champion_name = teamName;

                            getTeamName(team_id_2);
                            var second_id = team_id_2;
                            var second_name = teamName;
                        }else{
                            getTeamName(team_id_2);
                            var champion_id = team_id_2;
                            var champion_name = teamName;

                            getTeamName(team_id_1);
                            var second_id = team_id_1;
                            var second_name = teamName;
                        }
                        $('.title-name').text(name);
                        $('.winner-name').text(champion_name);
                        $('.for-champion_name').text(champion_name);
                        $('.for-second_name').text(second_name);
                    }
                })
            },
        });

        function getTeamName(team_id){
            $.ajax({
                type: 'get',
                url: `{!! env('BRACKET_API_URL') !!}teams/${team_id}`,
                async: false,
                success: function(e){
                    teamName = e.team_name;
                },
            });
        }

        $(document).on('change','.bracket-select', function(){
            var new_key = $(this).val();
            var old_key = $('input[name=key]').val();

            $('.avatar-'+old_key).hide();
            $('.tournamentWinner-title-'+old_key).hide();
            $('.tournamentWinner-list-'+old_key).hide();
            $('.table-list1-'+old_key).hide();
            $('.table-list2-'+old_key).hide();

            $('.avatar-'+new_key).show();
            $('.tournamentWinner-title-'+new_key).show();
            $('.tournamentWinner-list-'+new_key).show();
            $('.table-list1-'+new_key).show();
            $('.table-list2-'+new_key).show();

            $('input[name=key]').val(new_key);
        });
    });
</script>
@stop

@section('Content')
@if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
<div class="page-banner" style="background-image: url(https://www.atgames.net/leaderboards/images/page-banner-schedule.jpg);">
@else
<div class="page-banner" style="background-image: url(/images/page-banner-schedule.jpg);">
@endif
    <div class="title"><span>Schedule</span> of Events</div>
</div>
<div class="page">
    <div class="container">
        <div class="page-inner">
            <div class="tournament">
                <div class="tournament-header">
                    <div class="tournament-img">
                        <img src="{{ asset('/images/event-tournament.svg') }}">
                    </div>
                    <div class="tournament-title">Tournament</div>
                </div>
                {!! Form::hidden('event_id', @$event_id) !!}
                {!! Form::hidden('response', @$response) !!}
                {!! Form::hidden('key', '') !!}
                {!! Form::hidden('results_url', @action('CurlUSAAPI@viewEventAllData')) !!}
                <div class="eventInfo">
                    <div class="eventInfo-title">{{ @$response_data['name'] }}</div>
                    {{-- <div class="eventInfo-titleSub">
                        Asteroids Delux, Agus, Art of Fighting, Alpha Mission, Gondomania, Dragon Gun, Mark of The Wolves, Andro Runos
                    </div> --}}
                    <p>{!! nl2br(e(@$response_data['description'])) !!}</p>
                    <p>{{ @$response_data['start'] }} - {{ @$response_data['end'] }} 
                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            <a href="https://www.atgames.net/leaderboards/schedule/calendar" class="link-underline">View Calendar</a>
                        @else
                            <a href="{!! action('Frontend\Schedule@pageScheduleCalendar') !!}" class="link-underline">View Calendar</a>
                        @endif
                    </p>
                    <div class="eventInfo-btn">
                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            <a href="https://www.atgames.net/leaderboards/tournament/brackrt/{{ @$response_data['id'] }}" class="btn btn-gradient">View Brackrt</a>
                        @else
                            <a href="{!! action('Frontend\Tournament@pageTournamentBrackrt', array('event_id' => @$response_data['id'])) !!}" class="btn btn-gradient">View Brackrt</a>
                        @endif
                        <div class="status status-expired">
                            <i class="icon-an-expired" aria-hidden="true"></i>Expired
                        </div>
                    </div>
                </div>
            </div>
            <div class="bannerTournament"><span>Tournament </span> Winners!</div>
            <div class="bracket">
                <div class="bracket-header">
                    <form class="form-sp">
                        <div class="eventInfo-title eventHeader-titleSub selectSp2 title-name">
                                
                        </div>
                        <!-- <div class="form-group">
                            <label for="game">Game</label>
                            <div class="input-border select selectSp">
                                <select id="game" class="form-control">
                                    <option selected>Bracket A</option>
                                    <option>Bracket B</option>
                                    <option>Bracket C</option>
                                </select>
                            </div>
                        </div> -->
                    </form>
                </div>
                <div class="tournamentWinner">
                    <div class="avatar">
                        <div class="img-by img-by-1by1">
                            <img src="/images/avatar-01.svg" alt="title">
                        </div>
                    </div>
                    <div class="tournamentWinner-title winner-name"></div>
                    <div class="tournamentWinner-list">
                        <div class="item winner">
                            <div class="avatar">
                                <div class="img-by img-by-1by1">
                                    <img src="{!! asset('/images/avatar-01.svg') !!}" alt="title">
                                </div>
                            </div>
                            <div class="item-title">
                                <div class="item-title-inner">
                                    <strong class="for-champion_name"></strong>
                                </div>
                            </div>
                        </div>
                        <div class="item loser">
                            <div class="avatar">
                                <div class="img-by img-by-1by1">
                                    <img src="{!! asset('/images/avatar-01.svg') !!}" alt="title">
                                </div>
                            </div>
                            <div class="item-title">
                                <div class="item-title-inner">
                                    <strong class="for-second_name"></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="listTournament">
                <div id="sliderGameFinal">
                    @foreach(@$response_data['games'] as $key_data => $value_data)
                    <div class="item">
                        <div class="game">
                            <div class="game-border">
                                <div class="game-inner">
                                    <div class="title"><span>{{ $key_data+1 }}</span>{{ $value_data['name'] }}</div>
                                    <div class="cover">
                                        <div class="img-by img-by-5by2">
                                            <img src="{{ $value_data['boxart'] }}" alt="{{ $value_data['name'] }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- <div class="tournamentRank deco">
                <div class="tournamentRank-inner tournamentrank-list">
                    
                </div>
                <div class="export">
                    <button class="btn btn-border" type="button" id="search">
                        <div>
                            <i class="icon-log-out" aria-hidden="true"></i>
                            Export
                        </div>
                    </button>
                </div>
            </div> -->
        </div>
    </div>
</div>
@stop

@section('Js')

@stop