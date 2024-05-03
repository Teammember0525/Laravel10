@extends('Frontend.templates.layout')
@extends('Frontend.templates.nav')
@extends('Frontend.templates.footer')

@section('Title','Tournament Results')
@section('Schedule','active')

@section('HeadContent')
<script>
    $(document).ready(function () {
        var teamName = '';

        var event_id = $('.tournament').find('input[name=event_id]').val();
        var url = $('.tournament').find('input[name=url]').val();

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
                var status = 'Progress';

                $.each(e.brackets, function(okey, ovalue){
                    var count_rounds = ovalue.rounds.length;
                    var number_of_brackets = e.number_of_brackets;

                    var count_rounds = ovalue.rounds.length;
                    var number_of_brackets = e.number_of_brackets;
                    
                    if(Math.pow(2, count_rounds-1) == number_of_brackets){
                        var status = 'End';
                    }
                    
                    $.each(e.brackets, function(key, value){
                        var option1 = 
                            `${name}`;

                        var option2 = 
                            `<option value="${value.instance_index+1}">Bracket ${value.instance_index+1}</option>`;
                            
                        var option3 = 
                            `<div id="controlsBracket" class="controlsBracket">
                                <button class="btn-controls btn-controls-left" type="button">
                                    <i class="icon-chevron-left" aria-hidden="true"></i>
                                </button>
                                <button class="btn-controls btn-controls-right" type="button">
                                    <i class="icon-chevron-right" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div id="sliderBracket round-line">`;

                                $.each(value.rounds, function(key2, value2){
                                    
                                    var branchs_length = value2.branchs.length;
                                    option3 += 
                                    `<div class="item">
                                        <div class="round">

                                            <input type="hidden" name="rounds_count" value="${value.rounds.length}">
                                            <input type="hidden" name="bracket_now" value="1">
                                            <input type="hidden" name="events_now" value="${value.instance_index+1}">
                                            <input type="hidden" name="round_now" value="${value2.round_index+1}">

                                            <div class="round-${value2.round_index+1}-name round-header bracket-1-events-${value.instance_index+1}-round-${value2.round_index+1}" style="display: none;">Round ${value2.round_index+1}</div>
                                            <div class="round-body bracket-1-events-${value.instance_index+1}-round-${value2.round_index+1}" style="display: none;">
                                                <div class="round-status"><strong></strong><span id="timecount${key2}" class="timecount${key2}"></span></div>`;

                                                    if(branchs_length == 1){
                                                        option3 += `<div class="round-list bracket-line">`;
                                                    }else{
                                                        option3 += `<div class="round-list line bracket-line">`;
                                                    }

                                                    $.each(value2.branchs, function(key3, value3){
                                                        if((key3%2) == 0){
                                                            option3 += `<div class="item">`;
                                                        }
                                                        
                                                        option3 += `<div class="round-box">
                                                            <div class="round-box-inner">
                                                                <ul class="round-team">`;

                                                                    var bracket_team_id_1 = 0;
                                                                    var bracket_score_1 = 0;
                                                                    var bracket_team_id_2 = 0;
                                                                    var bracket_score_2 = 0;

                                                                    $.each(value3, function(ookey4, oovalue4){
                                                                        if(ookey4 == 0){
                                                                            bracket_team_id_1 = oovalue4.team_id;
                                                                            bracket_score_1 = oovalue4.current_score;
                                                                        }else{
                                                                            bracket_team_id_2 = oovalue4.team_id;
                                                                            bracket_score_2 = oovalue4.current_score;
                                                                        }
                                                                    })

                                                                    $.each(value3, function(key4, value4){
                                                                        getTeamName(value4.team_id);
                                                                        if((bracket_score_1 > bracket_score_2)||bracket_score_1 == bracket_score_2){
                                                                            if(bracket_team_id_1 == value4.team_id){
                                                                                if(value4.team_id == -1){
                                                                                    option3 += `<li>
                                                                                        <div class="round-team-title">
                                                                                            <span> - </span>
                                                                                            <strong> - </strong>
                                                                                        </div>
                                                                                        <div class="round-team-score"></div>
                                                                                    </li>`;
                                                                                }else{
                                                                                    option3 += `<li>
                                                                                        <div class="round-team-title">
                                                                                            <span>${value4.branch_index+1}</span>
                                                                                            <strong>${teamName}</strong>
                                                                                        </div>
                                                                                        <div class="round-team-score">${value4.current_score}</div>
                                                                                    </li>`;
                                                                                }
                                                                            }else{
                                                                                if(value4.team_id == -1){
                                                                                    option3 += `<li>
                                                                                        <div class="round-team-title">
                                                                                            <span> - </span>
                                                                                            <strong> - </strong>
                                                                                        </div>
                                                                                        <div class="round-team-score"></div>
                                                                                    </li>`;
                                                                                }else{
                                                                                    option3 += `<li>
                                                                                        <div class="round-team-title">
                                                                                            <span>${value4.branch_index+1}</span>
                                                                                            <strong>${teamName}</strong>
                                                                                        </div>
                                                                                        <div class="round-team-score">${value4.current_score}</div>
                                                                                    </li>`;
                                                                                }
                                                                            }
                                                                        }else if(bracket_score_2 > bracket_score_1){
                                                                            if(bracket_team_id_2 == value4.team_id){
                                                                                if(value4.team_id == -1){
                                                                                    option3 += `<li>
                                                                                        <div class="round-team-title">
                                                                                            <span> - </span>
                                                                                            <strong> - </strong>
                                                                                        </div>
                                                                                        <div class="round-team-score"></div>
                                                                                    </li>`;
                                                                                }else{
                                                                                    option3 += `<li>
                                                                                        <div class="round-team-title">
                                                                                            <span>${value4.branch_index+1}</span>
                                                                                            <strong>${teamName}</strong>
                                                                                        </div>
                                                                                        <div class="round-team-score">${value4.current_score}</div>
                                                                                    </li>`;
                                                                                }
                                                                            }else{
                                                                                if(value4.team_id == -1){
                                                                                    option3 += `<li>
                                                                                        <div class="round-team-title">
                                                                                            <span> - </span>
                                                                                            <strong> - </strong>
                                                                                        </div>
                                                                                        <div class="round-team-score"></div>
                                                                                    </li>`;
                                                                                }else{
                                                                                    option3 += `<li>
                                                                                        <div class="round-team-title">
                                                                                            <span>${value4.branch_index+1}</span>
                                                                                            <strong>${teamName}</strong>
                                                                                        </div>
                                                                                        <div class="round-team-score">${value4.current_score}</div>
                                                                                    </li>`;
                                                                                }
                                                                            }
                                                                        }
                                                                    })
                                                                    
                                                                option3 += `</ul>
                                                            </div>
                                                        </div>`;

                                                        if((key3%2) != 0){
                                                            option3 += `</div>`;
                                                        }
                                                    })

                                                option3 += `</div>
                                            </div>
                                        </div>
                                    </div>`;
                                })

                            option3 += `</div>`;
                        
                        $('.selectSp2').append(option1);
                        $('.event-select').append(option2);
                        $('.slider-bracket').append(option3);
                        $('.bracket-1-events-1-round-1').show();

                        let semi = (count_rounds-2);
                        $('.round-' + semi + '-name').text('SEMI-FINAL Round');

                        let finals = (count_rounds-1);
                        $('.round-' + finals + '-name').text('FINAL Round');

                        $('.round-' + count_rounds + '-name').text('FINAL WINNER');
                        
                    })
                    $('#resultsModal').modal('hide');
                    $('#bracketModal').modal('show');
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

        $(document).on('change','.event-select', function(){
            
            var bracket_now = $(this).parents('.all-bracket-model').find('[name=bracket_now]').val();
            var events_now = $(this).parents('.all-bracket-model').find('[name=events_now]').val();
            var round_now = $(this).parents('.all-bracket-model').find('[name=round_now]').val();
            var new_events = $(this).val();

            $('.bracket-'+bracket_now+'-events-'+events_now+'-round-'+round_now).hide();
            $('.bracket-'+bracket_now+'-events-'+new_events+'-round-1').show();

            $(this).parents('.all-bracket-model').find('[name=events_now]').val(new_events);
            $(this).parents('.all-bracket-model').find('[name=round_now]').val(1);
            
        });
        
        $(document).on('click','.btn-controls-right', function(){
            
            var rounds_count = $(this).parents('.slider-bracket').find('[name=rounds_count]').val();
            var bracket_now = $(this).parents('.slider-bracket').find('[name=bracket_now]').val();
            var events_now = $(this).parents('.slider-bracket').find('[name=events_now]').val();
            var round_now = $(this).parents('.slider-bracket').find('[name=round_now]').val();
            var new_count = parseInt(round_now)+1;
            
            if(new_count > rounds_count){
                $('.bracket-'+bracket_now+'-events-'+events_now+'-round-'+round_now).hide();
                $('.bracket-'+bracket_now+'-events-'+events_now+'-round-1').show();

                $(this).parents('.slider-bracket').find('[name=round_now]').val(1);
            }else{
                $('.bracket-'+bracket_now+'-events-'+events_now+'-round-'+round_now).hide();
                $('.bracket-'+bracket_now+'-events-'+events_now+'-round-'+new_count).show();

                $(this).parents('.slider-bracket').find('[name=round_now]').val(new_count);
            }
            
        });

        $(document).on('click','.btn-controls-left', function(){
            
            var rounds_count = $(this).parents('.slider-bracket').find('[name=rounds_count]').val();
            var bracket_now = $(this).parents('.slider-bracket').find('[name=bracket_now]').val();
            var events_now = $(this).parents('.slider-bracket').find('[name=events_now]').val();
            var round_now = $(this).parents('.slider-bracket').find('[name=round_now]').val();
            var new_count = parseInt(round_now)-1;
            
            if(new_count <= 0){
                $('.bracket-'+bracket_now+'-events-'+events_now+'-round-'+round_now).hide();
                $('.bracket-'+bracket_now+'-events-'+events_now+'-round-'+rounds_count).show();

                $(this).parents('.slider-bracket').find('[name=round_now]').val(rounds_count);
            }else{
                $('.bracket-'+bracket_now+'-events-'+events_now+'-round-'+round_now).hide();
                $('.bracket-'+bracket_now+'-events-'+events_now+'-round-'+new_count).show();

                $(this).parents('.slider-bracket').find('[name=round_now]').val(new_count);
            }
            
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
    <div class="title"><span>Tournament</span> Results</div>
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
                {!! Form::hidden('url', @action('CurlUSAAPI@viewEventAllData')) !!}
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
                            <a href="https://www.atgames.net/leaderboards/tournament/result/{{ @$response_data['id'] }}" class="btn btn-gradient">Results</a>
                        @else
                            <a href="{!! action('Frontend\Tournament@pageTournamentResult', array('event_id' => @$response_data['id'])) !!}" class="btn btn-gradient">Results</a>
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
                        <div class="form-group">
                            <label for="game">Game</label>
                            <div class="input-border select selectSp">
                                <!-- <select id="game" class="form-control event-select">
                                    <option selected>Bracket A</option>
                                    <option>Bracket B</option>
                                    <option>Bracket C</option>
                                </select> -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="sliderBracket slider-bracket">
                    {{-- <div id="controlsBracket">
                        <button class="btn-controls btn-controls-left" type="button">
                            <i class="icon-chevron-left" aria-hidden="true"></i>
                        </button>
                        <button class="btn-controls btn-controls-right" type="button">
                            <i class="icon-chevron-right" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div id="sliderBracket">
                        <div class="item">
                            <div class="round">
                                <div class="round-header">Round 1</div>
                                <div class="round-body">
                                    <div class="round-status"><strong>In Progress: </strong>01:38:05</div>
                                    <div class="round-list line">
                                        <div class="item">
                                            <div class="round-box">
                                                <div class="round-box-inner">
                                                    <ul class="round-team">
                                                        <li>
                                                            2<strong>YOUR TEAM</strong>
                                                        </li>
                                                        <li>
                                                            3<strong>YOUR TEAM</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="round-box">
                                                <div class="round-box-inner">
                                                    <ul class="round-team">
                                                        <li>
                                                            2<strong>YOUR TEAM</strong>
                                                        </li>
                                                        <li>
                                                            3<strong>YOUR TEAM</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="round-box">
                                                <div class="round-box-inner">
                                                    <ul class="round-team">
                                                        <li>
                                                            5<strong>YOUR TEAM</strong>
                                                        </li>
                                                        <li>
                                                            6<strong>YOUR TEAM</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="round-box">
                                                <div class="round-box-inner">
                                                    <ul class="round-team">
                                                        <li>
                                                            7<strong>YOUR TEAM</strong>
                                                        </li>
                                                        <li>
                                                            8<strong>YOUR TEAM</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="round">
                                <div class="round-header">SEMI-Finals</div>
                                <div class="round-body">
                                    <div class="round-status"><strong>In Progress: </strong>01:38:05</div>
                                    <div class="round-list line">
                                        <div class="item">
                                            <div class="round-box">
                                                <div class="round-box-inner">
                                                    <ul class="round-team">
                                                        <li>
                                                            1<strong class="winner">YOUR TEAM</strong>
                                                        </li>
                                                        <li>
                                                            2<strong class="loser">YOUR TEAM</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="round-box">
                                                <div class="round-box-inner">
                                                    <ul class="round-team">
                                                        <li>
                                                            1<strong class="winner">YOUR TEAM</strong>
                                                        </li>
                                                        <li>
                                                            2<strong class="loser">YOUR TEAM</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="round">
                                <div class="round-header">Finals</div>
                                <div class="round-body">
                                    <div class="round-status"><strong>In Progress: </strong>01:38:05</div>
                                    <div class="round-list">
                                        <div class="item">
                                            <div class="round-box">
                                                <div class="round-box-inner">
                                                    <ul class="round-team">
                                                        <li>
                                                            1<strong class="winner">YOUR TEAM</strong>
                                                        </li>
                                                        <li>
                                                            2<strong class="loser">YOUR TEAM</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('Js')

@stop