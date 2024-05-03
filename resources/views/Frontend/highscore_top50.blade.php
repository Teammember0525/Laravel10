@extends('Frontend.templates.layout')
@extends('Frontend.templates.nav')
@extends('Frontend.templates.footer')

@section('Title','High Score')
@section('Schedule','active')

@section('HeadContent')
<script>
    $(document).ready(function () {
        var slider = tns({
            container: '#sliderGameFinal50',
            controls: false,
            nav: true,
            navPosition: "top",
            mouseDrag: true,
            autoplay: false,
            autoplayButtonOutput: false,
            rewind: false,
            gutter: 0,
            responsive: {
                0: {
                    items: 1
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
                            <a href="https://www.atgames.net/leaderboards/highscore/result/{{ $event_id }}" class="btn btn-gradient">Back to Results</a>
                        @else
                            <a href="{!! action('Frontend\Highscore@pageHighScoreResult', array('event_id' => $event_id)) !!}" class="btn btn-gradient">Back to Results</a>
                        @endif
                        <div class="status status-{{ strtolower(@$response_data['status']) }}">
                            <i class="icon-an-{{ strtolower(@$response_data['status']) }}" aria-hidden="true"></i>{{ @$response_data['status'] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="bannerTournament"><span>High Score </span>Top 50</div>
            {{-- <div class="tournamentWinner">
                <div class="tournamentWinner-title">Your Team</div>
                <div class="tournamentWinner-game">Dragon Gun Winner!</div>
            </div> --}}
            <div class="listTournament50">
                <div id="sliderGameFinal50">
                    @foreach(@$response_data['games'] as $key => $value)
                    <div class="item">
                        <div class="item-game">
                            <div class="game">
                                <div class="game-border">
                                    <div class="game-inner">
                                        <div class="title"><span>{{ $key+1 }}</span>{{ $value['name'] }}</div>
                                        <div class="cover">
                                            <div class="img-by img-by-5by2">
                                                <img src="{{ $value['boxart'] }}" alt="{{ $value['name'] }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">Place</th>
                                    <th scope="col">Team Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($value['rankings'] != [])
                                    @for($i=0;$i< count($value['rankings']);$i++)
                                    <tr>
                                        <th scope="row">{{ $i+1 }}</th>
                                        <td>{!! @$value['rankings'][$i]['user_name'] !!}</td>
                                    </tr>
                                    @endfor
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('Js')

@stop