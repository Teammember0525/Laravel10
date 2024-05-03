@extends('Frontend.templates.layout')
@extends('Frontend.templates.nav')
@extends('Frontend.templates.footer')

@section('Title','High Score')
@section('Schedule','active')

@section('HeadContent')
<script>
    $(document).ready(function () {
        
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
                        <img src="{{ asset('/images/event-highScore.svg') }}">
                    </div>
                    <div class="tournament-title">High Score</div>
                </div>
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
                            <a href="https://www.atgames.net/leaderboards/highscore/top50/{{ $event_id }}" class="btn btn-gradient">Top 50 Scores</a>
                        @else
                            <a href="{!! action('Frontend\Highscore@pageHighScoreTop50', array('event_id' => $event_id)) !!}" class="btn btn-gradient">Top 50 Scores</a>
                        @endif
                        <div class="status status-{{ strtolower(@$response_data['status']) }}">
                            <i class="icon-an-{{ strtolower(@$response_data['status']) }}" aria-hidden="true"></i>{{ @$response_data['status'] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="bannerTournament"><span>High Score </span>Leaderboard</div>
            <div class="listGame">
                @foreach(@$response_data['games'] as $key => $value)
                <div class="item">
                    <div class="game">
                        <div class="game-border">
                            <div class="game-inner">
                                <div class="title">{{ $value['name'] }}</div>
                                <div class="cover">
                                    <div class="img-by img-by-5by2">
                                        <img src="{{ $value['boxart'] }}" alt="{{ $value['name'] }}">
                                    </div>
                                </div>
                                <ul class="board">
                                    @if($value['rankings'] != [])
                                        @if(count($value['rankings']) >= 3)
                                            @for($i=0;$i<3;$i++)
                                            <li>
                                                <span>{!! @$value['rankings'][$i]['user_name'] !!}</span>
                                                <strong>{!! number_format(@$value['rankings'][$i]['score']) !!}</strong>
                                            </li>
                                            @endfor
                                        @else
                                            @for($i=0;$i< count($value['rankings']);$i++)
                                            <li>
                                                <span>{!! @$value['rankings'][$i]['user_name'] !!}</span>
                                                <strong>{!! number_format(@$value['rankings'][$i]['score']) !!}</strong>
                                            </li>
                                            @endfor
                                        @endif
                                    @endif
                                </ul>
                            </div>
                        </div>
                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            <a href="https://www.atgames.net/leaderboards/device/top50/{{ @$value['game_id'] }}" title="Top 50 Scores" class="link-underline">Top 50 Scores</a>
                        @else
                            <a href="{!! action('Frontend\Device@pageDeviceTop50', array('game_id' => @$value['game_id'])) !!}" title="Top 50 Scores" class="link-underline">Top 50 Scores</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@stop

@section('Js')

@stop