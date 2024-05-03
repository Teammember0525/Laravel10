@extends('Frontend.templates.layout')
@extends('Frontend.templates.nav')
@extends('Frontend.templates.footer')

@section('Title','Device')
@section('Device','active')

@section('HeadContent')
<script>
    $(document).ready(function () {

        $('#leaderboard_loadmore').on('click', function(){

            var host_name = window.location.hostname;
            if(host_name == 'www.atgames.net' || host_name == 'acnet-lb.atgames.net'){
                var url = 'https://www.atgames.net/leaderboards/leaderboard/after';
                var base_path = "https://www.atgames.net/leaderboards";
            }else{
                var url = $('[name=url]').val();
                var base_path = "{!! url('/') !!}";
            }

            var last_game_id = $('[name=last_game_id]').val();
            var series = $('[name=series]').val();
            var prefix = $('[name=prefix]').val();
            var keyword = $('[name=keyword]').val();

            $(this).attr('disabled', true);

            $.ajax({
                type: 'get',
                url: url,
                data: {
                    'after' : last_game_id,
                    'series' : series,
                    'prefix' : prefix,
                    'keyword' : keyword,
                },
                success: function(e){
                    e = JSON.parse(e);

                    $.each(e, function(key, value){
                        url = base_path + `/device/top50/${value.game_id}?series=${series}`;
                        var option1 =
                            `<div class="item">
                                <div class="game">
                                    <div class="game-border">
                                        <div class="game-inner">
                                            <div class="title">${value.name}</div>
                                            <div class="cover">
                                                <div class="img-by img-by-5by2">
                                                    <img src="${value.boxart}" alt="title">
                                                </div>
                                            </div>
                                            <ul class="board">`;

                                            var count = 0;
                                            $.each(value.rankings, function(key2, value2){

                                                option1 += `<li>
                                                    <span>${value2.user_name}</span>
                                                    <strong>${value2.score}</strong>
                                                </li>`;
                                                count++;
                                                if(count==3){
                                                    return false;
                                                }
                                            })

                            option1 += `</ul>
                                        </div>
                                    </div>
                                    <a href="${url}" title="Top 50 Scores" class="link-underline">Top 50 Scores</a>
                                </div>
                            </div>`;
                        $('[name=last_game_id]').val(value.game_id);
                        $('.listGame').append(option1);
                    })
                    if(e.length < 8){
                        $('#leaderboard_loadmore').css('display', 'none');
                    }
                },
                complete: function(){
                    $('#leaderboard_loadmore').removeAttr('disabled');
                }
            });
        });
    });
</script>
@stop

@section('Content')
<div class="page-banner"></div>
<div class="page">
    <div class="container">
        <div class="page-inner">
            <div class="filter filterDevice">
                <div class="filter-01">
                    <div class="navLink">
                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            <a href="{{ @$url_action }}?series=&prefix={{ $prefix }}" class="btn btn-link @if($series == '') active @endif">
                                <i class="icon-an-all" aria-hidden="true"></i>
                                <span>All</span>
                            </a>
                        @else
                            <a href="{!! action(@$url_action, array('series' => '', 'prefix' => $prefix)) !!}" class="btn btn-link @if($series == '') active @endif">
                                <i class="icon-an-all" aria-hidden="true"></i>
                                <span>All</span>
                            </a>
                        @endif

                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            @foreach(@$unique_array as $key2 => $value2)
                                @switch($value2['series_name'])
                                    @case('Ultimate')
                                        <a href="https://www.atgames.net/leaderboards/device?series={{ $value2['series_id'] }}&prefix={{ $prefix }}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-ultimate" aria-hidden="true"></i>
                                            <span>Ultimate</span>
                                        </a>
                                        @break
                                    @case('Gamer')
                                        <a href="https://www.atgames.net/leaderboards/device?series={{ $value2['series_id'] }}&prefix={{ $prefix }}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-gamer" aria-hidden="true"></i>
                                            <span>Gamer</span>
                                        </a>
                                        @break
                                    @case('Flashback')
                                        <a href="https://www.atgames.net/leaderboards/device?series={{ $value2['series_id'] }}&prefix={{ $prefix }}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-flashback" aria-hidden="true"></i>
                                            <span>Flashback</span>
                                        </a>
                                        @break
                                    @case('Pinball')
                                        <a href="https://www.atgames.net/leaderboards/device?series={{ $value2['series_id'] }}&prefix={{ $prefix }}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-pinball" aria-hidden="true"></i>
                                            <span>Pinball</span>
                                        </a>
                                        @break
                                    @case('Connect')
                                        <a href="https://www.atgames.net/leaderboards/device?series={{ $value2['series_id'] }}&prefix={{ $prefix }}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-connect" aria-hidden="true"></i>
                                            <span>Connect</span>
                                        </a>
                                        @break
                                    @case('Core')
                                        <a href="https://www.atgames.net/leaderboards/device?series={{ $value2['series_id'] }}&prefix={{ $prefix }}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
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
                                        <a href="{!! action(@$url_action, array('series' => $value2['series_id'], 'prefix' => $prefix)) !!}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-ultimate" aria-hidden="true"></i>
                                            <span>Ultimate</span>
                                        </a>
                                        @break
                                    @case('Gamer')
                                        <a href="{!! action(@$url_action, array('series' => $value2['series_id'], 'prefix' => $prefix)) !!}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-gamer" aria-hidden="true"></i>
                                            <span>Gamer</span>
                                        </a>
                                        @break
                                    @case('Flashback')
                                        <a href="{!! action(@$url_action, array('series' => $value2['series_id'], 'prefix' => $prefix)) !!}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-flashback" aria-hidden="true"></i>
                                            <span>Flashback</span>
                                        </a>
                                        @break
                                    @case('Pinball')
                                        <a href="{!! action(@$url_action, array('series' => $value2['series_id'], 'prefix' => $prefix)) !!}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-pinball" aria-hidden="true"></i>
                                            <span>Pinball</span>
                                        </a>
                                        @break
                                    @case('Connect')
                                        <a href="{!! action(@$url_action, array('series' => $value2['series_id'], 'prefix' => $prefix)) !!}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-connect" aria-hidden="true"></i>
                                            <span>Connect</span>
                                        </a>
                                        @break
                                    @case('Core')
                                        <a href="{!! action(@$url_action, array('series' => $value2['series_id'], 'prefix' => $prefix)) !!}" class="btn btn-link @if($series == $value2['series_id']) active @endif">
                                            <i class="icon-an-core" aria-hidden="true"></i>
                                            <span>Core</span>
                                        </a>
                                        @break
                                    @default
                                        @break
                                @endswitch
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="filter-02">
                    <div class="search">
                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            <form method="GET" action="https://www.atgames.net/leaderboards/device" accept-charset="UTF-8">
                        @else
                            {!! Form::open(array('url' => route('device'), 'method' => 'get')) !!}
                        @endif
                        <div class="input-border">
                            {!! Form::text('keyword', $keyword, ['id' => 'keyword', 'class' => 'form-control', 'placeholder' => 'Search Game Title']) !!}
                        </div>
                        <button class="btn" type="submit" id="search">
                            <i class="icon-search" aria-hidden="true"></i>
                        </button>
                        {!! Form::hidden('series', @$series) !!}
                        {!! Form::hidden('prefix', @$prefix) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="bannerDevice">
                @switch($series_name)
                    @case('Ultimate')
                        <img src="{!! asset('/images/device-ultimate.png') !!}" alt="Ultimate">
                        @break
                    @case('Gamer')
                        <img src="{!! asset('/images/device-gamer.png') !!}" alt="Gamer">
                        @break
                    @case('Pinball')
                        <img src="{!! asset('/images/device-pinball.png') !!}" alt="Pinball">
                        @break
                    @case('Flashback')
                        <img src="{!! asset('/images/device-flashback.png') !!}" alt="Flashback">
                        @break
                    @case('Connect')
                        <img src="{!! asset('/images/device-connect.png') !!}" alt="Flashback">
                        @break
                    @case('Core')
                        <img src="{!! asset('/images/device-core.png') !!}" alt="Flashback">
                        @break
                    @default
                        <img src="{!! asset('/images/device-all.png') !!}" alt="All">
                        @break
                @endswitch
            </div>
            {{-- @if($search_result)
                <div class="listSearch">
                    <div class="listSearch-title">Search Results</div>
                    @foreach(@$search_result as $key3 => $value3)
                        <div class="item">
                            <div class="game">
                                <div class="game-border">
                                    <div class="game-inner">
                                        <div class="title">{!! @$value3['name'] !!}</div>
                                        <div class="cover">
                                            <div class="img-by img-by-5by2">
                                                <img src="{!! @$value3['boxart'] !!}" alt="title">
                                            </div>
                                        </div>
                                        <ul class="board">
                                            @for($j=0;$j<3;$j++)
                                                <li>
                                                    <span>{!! @$value3['rankings'][$j]['user_name'] !!}</span>
                                                    <strong>{!! number_format(@$value3['rankings'][$j]['score']) !!}</strong>
                                                </li>
                                            @endfor
                                        </ul>
                                    </div>
                                </div>
                                <a href="{!! action('Frontend\Device@pageDeviceTop50', array('game_id' => @$value3['game_id'])) . '?series=' . Request::get('series') !!}" title="Top 50 Scores" class="link-underline">Top 50 Scores</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif --}}
            <div class="filteraz">
                @include('Frontend.templates.filteraz')
            </div>
            <div class="listGame">
                @foreach (@$leaderboard_data as $key => $value)
                <div class="item">
                    <div class="game">
                        <div class="game-border">
                            <div class="game-inner">
                                <div class="title">{!! @$value['name'] !!}</div>
                                <div class="cover">
                                    <div class="img-by img-by-5by2">
                                        <img src="{!! @$value['boxart'] !!}" alt="title">
                                    </div>
                                </div>
                                <ul class="board">
                                    @if(!empty($value['rankings']) && $value['rankings'] != [])
                                        @if(count($value['rankings']) >= 3)
                                            @for($i=0;$i<3;$i++)
                                            <li>
                                                <span>{!! @$value['rankings'][$i]['user_name'] !!}</span>
                                                <strong>{!! @$value['rankings'][$i]['score'] !!}</strong>
                                            </li>
                                            @endfor
                                        @else
                                            @for($i=0;$i< count($value['rankings']);$i++)
                                            <li>
                                                <span>{!! @$value['rankings'][$i]['user_name'] !!}</span>
                                                <strong>{!! @$value['rankings'][$i]['score'] !!}</strong>
                                            </li>
                                            @endfor
                                        @endif
                                    @endif
                                </ul>
                            </div>
                        </div>
                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            <a href="https://www.atgames.net/leaderboards/device/top50/{{ @$value['game_id'] }}?series={{ @$series }}" title="Top 50 Scores" class="link-underline">Top 50 Scores</a>
                        @else
                            <a href="{!! action('Frontend\Device@pageDeviceTop50', array('game_id' => @$value['game_id'])) . '?series=' . Request::get('series') !!}" title="Top 50 Scores" class="link-underline">Top 50 Scores</a>
                        @endif
                    </div>
                </div>
                @endforeach
                {!! Form::hidden('last_game_id', @$last_game_id) !!}
                {!! Form::hidden('keyword', @$keyword) !!}
            </div>
        </div>
        @if(count($leaderboard_data) == 8)
        <div class="loadMore">
            <button id="leaderboard_loadmore" type="button" class="btn btn-gradient">Load More</button>
        </div>
        @endif
        {!! Form::hidden('url', @action('CurlAPI@getLeaderboardAfter')) !!}
    </div>
</div>
@stop

@section('Js')

@stop
