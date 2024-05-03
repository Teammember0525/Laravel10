@extends('Frontend.templates.layout')
@extends('Frontend.templates.nav')
@extends('Frontend.templates.footer')

@section('Title','Top 50 Scores')
@section('Device','active')

@section('HeadContent')
<script>
    $(document).ready(function () {

        $(document).on('click', '#back', function(){
            history.back();
        });

        $('#btn-example-load-more').on('click', function(){
            datatable.page.loadMore();
        });

        $('.no-log-friends').on('click', function(){

            $('#loginModal').modal('show');
        });

        $(document).on('click', '.print-page', function(){
            console.log(123);
            window.print();
        });

    });
</script>
@stop

@section('Content')
<div class="page-banner"></div>
<div class="page">
    <div class="container">
        <div class="page-inner">
            <div class="filter filterRank">
                <div class="filter-01">
                    <div class="navLink">
                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            <a href="{{ @$url_action }}?series=&game_id={{ $game_id }}&timeRange={{ $timeRange }}" class="btn btn-link @if($series == '') active @endif">
                                <i class="icon-an-all" aria-hidden="true"></i>
                                <span>All</span>
                            </a>
                        @else
                            <a href="{!! action(@$url_action, array('game_id' => $game_id, 'series' => '', 'timeRange' => $timeRange)) !!}" class="btn btn-link @if($series == '') active @endif">
                                <i class="icon-an-all" aria-hidden="true"></i>
                                <span>All</span>
                            </a>
                        @endif

                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            @foreach(@$unique_array as $key2 => $value2)
                                @switch($value2['series_name'])
                                    @case('Ultimate')
                                        <a href="https://www.atgames.net/leaderboards/device?series={{ $value2['series_id'] }}&game_id={{ $game_id }}&timeRange={{ $timeRange }}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-ultimate" aria-hidden="true"></i>
                                            <span>Ultimate</span>
                                        </a>
                                        @break
                                    @case('Gamer')
                                        <a href="https://www.atgames.net/leaderboards/device?series={{ $value2['series_id'] }}&game_id={{ $game_id }}&timeRange={{ $timeRange }}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-gamer" aria-hidden="true"></i>
                                            <span>Gamer</span>
                                        </a>
                                        @break
                                    @case('Flashback')
                                        <a href="https://www.atgames.net/leaderboards/device?series={{ $value2['series_id'] }}&game_id={{ $game_id }}&timeRange={{ $timeRange }}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-flashback" aria-hidden="true"></i>
                                            <span>Flashback</span>
                                        </a>
                                        @break
                                    @case('Pinball')
                                        <a href="https://www.atgames.net/leaderboards/device?series={{ $value2['series_id'] }}&game_id={{ $game_id }}&timeRange={{ $timeRange }}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-pinball" aria-hidden="true"></i>
                                            <span>Pinball</span>
                                        </a>
                                        @break
                                    @case('Connect')
                                        <a href="https://www.atgames.net/leaderboards/device?series={{ $value2['series_id'] }}&game_id={{ $game_id }}&timeRange={{ $timeRange }}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-connect" aria-hidden="true"></i>
                                            <span>Connect</span>
                                        </a>
                                        @break
                                    @case('Core')
                                        <a href="https://www.atgames.net/leaderboards/device?series={{ $value2['series_id'] }}&game_id={{ $game_id }}&timeRange={{ $timeRange }}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-core" aria-hidden="true"></i>
                                            <span>Core</span>
                                        </a>
                                        @break
                                    @default
                                        @break
                                @endswitch
                            @endforeach
                        @else
                            @foreach(@$unique_array as $key2 => $value2)
                                @switch($value2['series_name'])
                                    @case('Ultimate')
                                        <a href="{!! action(@$url_action, array('game_id' => $game_id, 'series' => $value2['series_id'], 'timeRange' => $timeRange)) !!}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-ultimate" aria-hidden="true"></i>
                                            <span>Ultimate</span>
                                        </a>
                                        @break
                                    @case('Gamer')
                                        <a href="{!! action(@$url_action, array('game_id' => $game_id, 'series' => $value2['series_id'], 'timeRange' => $timeRange)) !!}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-gamer" aria-hidden="true"></i>
                                            <span>Gamer</span>
                                        </a>
                                        @break
                                    @case('Flashback')
                                        <a href="{!! action(@$url_action, array('game_id' => $game_id, 'series' => $value2['series_id'], 'timeRange' => $timeRange)) !!}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-flashback" aria-hidden="true"></i>
                                            <span>Flashback</span>
                                        </a>
                                        @break
                                    @case('Pinball')
                                        <a href="{!! action(@$url_action, array('game_id' => $game_id, 'series' => $value2['series_id'], 'timeRange' => $timeRange)) !!}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-pinball" aria-hidden="true"></i>
                                            <span>Pinball</span>
                                        </a>
                                        @break
                                    @case('Connect')
                                        <a href="{!! action(@$url_action, array('game_id' => $game_id, 'series' => $value2['series_id'], 'timeRange' => $timeRange)) !!}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-connect" aria-hidden="true"></i>
                                            <span>Connect</span>
                                        </a>
                                        @break
                                    @case('Core')
                                        <a href="{!! action(@$url_action, array('game_id' => $game_id, 'series' => $value2['series_id'], 'timeRange' => $timeRange)) !!}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-core" aria-hidden="true"></i>
                                            <span>Core</span>
                                        </a>
                                        @break
                                    @default
                                        @break
                                @endswitch
                            @endforeach
                        @endif

                        @if(Session::has('token'))
                            @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                                <a href="{{ @$url_action }}?friends=friends&game_id={{ $game_id }}&timeRange={{ $timeRange }}" class="btn btn-link @if($friends != '') active @endif">
                                    <i class="icon-an-friends" aria-hidden="true"></i>
                                    <span>Friends</span>
                                </a>
                            @else
                                <a href="{!! action(@$url_action, array('friends' => 'friends', 'game_id' => $game_id, 'timeRange' => $timeRange)) !!}" class="btn btn-link @if($friends != '') active @endif">
                                    <i class="icon-an-friends" aria-hidden="true"></i>
                                    <span>Friends</span>
                                </a>
                            @endif
                        @else
                            <a href="#" class="btn btn-link no-log-friends">
                                <i class="icon-an-friends" aria-hidden="true"></i>
                                <span>Friends</span>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="filter-02">
                    <button class="btn btn-border" type="button" id="back">
                        <div>
                            <i class="icon-chevron-left" aria-hidden="true"></i>
                            Back
                        </div>
                    </button>
                </div>
            </div>
            <div class="bannerRank">
                <div class="update">
                    <div class="item">
                        <span>Top 50 Scores: </span>Last updated {{ @$data['snapshot'] }}
                    </div>
                    <div class="item">
                        <span>Number of Entries: </span>{!! @$data['participants_count'] !!}
                    </div>
                </div>
                <div class="topScore">
                    <div class="topScore-info">
                        <div class="topScore-title">
                            <span>Top Score: </span> {!! @$data['rankings'][0]['user_name'] !!}
                        </div>
                        <div class="topScore-day">
                            <div class="topScore-king">
                                <i class="icon-an-crown" aria-hidden="true"></i>
                                King of the Hill <strong> {{ @$King_of_the_Hill }} Days</strong>
                            </div>
                            <div class="topScore-time">
                                {!! @$data['rankings'][0]['created_at'] !!}
                            </div>
                        </div>
                    </div>
                    <div class="topScore-cover">
                        <div class="game">
                            <div class="game-border">
                                <div class="game-inner">
                                    <div class="title">{!! @$data['name'] !!}</div>
                                    <div class="cover">
                                        <div class="img-by img-by-5by2">
                                            <img src="{!! @$data['boxart'] !!}" alt="title">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="listRank">
                <div class="export">
                    <button class="btn btn-border" type="button" id="search">
                        <div class="print-page">
                            <!-- <a href="#" onclick="window.open('http://tw.yahoo.com', 'PDF', config='height=500,width=500');"> -->
                            <i class="icon-log-out" aria-hidden="true"></i>
                            Export
                            <!-- </a> -->
                        </div>
                    </button>
                </div>
                <div class="navTab">
                    <div class="navTab-inner">
                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            <a class="@if($timeRange == '') active @endif" href="{{ @$url_action }}?series={{ $series }}&game_id={{ $game_id }}&timeRange=">All-Time</a>
                            <a class="@if($timeRange == 'monthly') active @endif" href="{{ @$url_action }}?series={{ $series }}&game_id={{ $game_id }}&timeRange=monthly">Monthly</a>
                            <a class="@if($timeRange == 'weekly') active @endif" href="{{ @$url_action }}?series={{ $series }}&game_id={{ $game_id }}&timeRange=weekly">Weekly</a>
                        @else
                            <a class="@if($timeRange == '') active @endif" href="{!! action(@$url_action, array('game_id' => $game_id, 'series' => $series, 'timeRange' => '')) !!}">All-Time</a>
                            <a class="@if($timeRange == 'monthly') active @endif" href="{!! action(@$url_action, array('game_id' => $game_id, 'series' => $series, 'timeRange' => 'monthly')) !!}">Monthly</a>
                            <a class="@if($timeRange == 'weekly') active @endif" href="{!! action(@$url_action, array('game_id' => $game_id, 'series' => $series, 'timeRange' => 'weekly')) !!}">Weekly</a>
                        @endif
                    </div>
                </div>
                <table id="table" class="table table-rank">
                    <thead>
                        <tr>
                            <th class="td-01 td-sp" scope="col">Rank</th>
                            <th class="td-02 td-sp" scope="col">Name</th>
                            <th class="td-03" scope="col">Initials</th>
                            <th class="td-04" scope="col">Device</th>
                            <th class="td-05" scope="col">Date</th>
                            <th class="td-06 td-sp" scope="col">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (@$data['rankings'] as $key => $value)
                        <tr class="@if(@$value['rank'] == 1) rank1 @elseif(@$value['rank'] == 2) rank2 @elseif(@$value['rank'] == 3) rank3 @endif">
                            <td class="td-01 td-sp" scope="row">{{ @$value['rank'] }}</th>
                            <td class="td-02 td-sp">{{ @$value['user_name'] }}</td>
                            <td class="td-03">{{ @$value['signature'] }}</td>
                            @switch($value['series'])
                                @case('Ultimate')
                                    <td class="td-04"><i class="icon-an-ultimate" aria-hidden="true" title="Ultimate"></i><b hidden>Ultimate</b></td>
                                    @break
                                @case('Gamer')
                                    <td class="td-04"><i class="icon-an-gamer" aria-hidden="true" title="Gamer"></i><b hidden>Gamer</b></td>
                                    @break
                                @case('Flashback')
                                    <td class="td-04"><i class="icon-an-flashback" aria-hidden="true" title="Flashback"></i><b hidden>Flashback</b></td>
                                    @break
                                @case('Pinball')
                                    <td class="td-04"><i class="icon-an-pinball" aria-hidden="true" title="Pinball"></i><b hidden>Pinball</b></td>
                                    @break
                                @case('Connect')
                                    <td class="td-04"><i class="icon-an-connect" aria-hidden="true" title="Connect"></i><b hidden>Connect</b></td>
                                    @break
                                @case('Core')
                                    <td class="td-04"><i class="icon-an-core" aria-hidden="true" title="Core"></i><b hidden>Core</b></td>
                                    @break
                                @default
                                    <td class="td-04">{{ $value['series'] }}</td>
                                    @break
                            @endswitch
                            <td class="td-05">{{ @$value['created_at'] }}</td>
                            <td class="td-06 td-sp">{{ @$value['score'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="loadMore">
            @if($data['rankings'] != [])
                <button id="btn-example-load-more" type="button" class="btn btn-gradient">Load More</button>
            @endif
        </div>
    </div>
</div>
@stop

@section('Js')

@stop
