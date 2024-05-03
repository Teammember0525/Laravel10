@extends('Frontend.templates.layout')
@extends('Frontend.templates.nav')
@extends('Frontend.templates.footer')

@section('Title','Titles')
@section('Titles','active')

@section('HeadContent')
<script>
    $(document).ready(function () {

        $('#leaderboard_loadmore').on('click', function(){

            var host_name = window.location.hostname;
            if(host_name == 'www.atgames.net' || host_name == 'acnet-lb.atgames.net'){
                var url = 'https://www.atgames.net/leaderboards/titles/after';
                var base_path = "https://www.atgames.net/leaderboards";
            }else{
                var url = $('[name=url]').val();
                var base_path = "{!! url('/') !!}";
            }

            var last_game_id = $('[name=last_game_id]').val();
            var count = $('[name=count]').val();
            var rule = $('[name=rule]').val();
            var prefix = $('[name=prefix]').val();
            var order = $('[name=order]').val();
            var friends = $('[name=friends]').val();
            var table = $('[name=table]').val();
            var table_rule = $('[name=table_rule]').val();
            var keyword = $('[name=keyword]').val();

            $(this).attr('disabled', true);

            $.ajax({
                type: 'get',
                async: false,
                url: url,
                data: {
                    'after' : last_game_id,
                    'rule' : rule,
                    'prefix' : prefix,
                    'order' : order,
                    'friends' : friends,
                    'table' : table,
                    'table_rule' : table_rule,
                    'keyword' : keyword,
                },
                success: function(e){
                    e = JSON.parse(e);

                    $.each(e, function(key, value){
                        var new_count = parseInt(key+1)+parseInt(count);
                        top50_url = base_path + `/device/top50/${value.game_id}`;
                        var option1 =
                            `<div class="item">
                                <div class="game">
                                    <div class="game-border">
                                        <div class="game-inner">
                                            <div class="title">
                                                <a href="${top50_url}" title="Asteroids Delux Top 50 Scores">
                                                    <span class="see-rank">${new_count}</span>${value.name}
                                                </a>
                                            </div>
                                            <div class="cover">
                                                <div class="img-by img-by-5by2">
                                                    <img src="${value.boxart}" alt="title">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="${top50_url}" title="Top 50 Scores" class="link-underline">Top 50 Scores</a>
                                </div>
                            </div>`;
                        $('[name=last_game_id]').val(value.game_id);
                        $('[name=count]').val(new_count);
                        $('.listTitles').append(option1);
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

        $('.no-log-friends').on('click', function(){

            $('#loginModal').modal('show');
        });

        $('#viewTitles-list').on('click', function(){
            var rank = $('.see-rank').last().text();
            var count = (24 - rank) / 8;
            for (var i = 1; i <= count; i++) {
                if(rank < 24){
                    $('#leaderboard_loadmore').trigger('click');
                    rank = parseInt(rank) + 8;
                }else{
                    break;
                }
            }
        })
    });
</script>
@stop

@section('Content')
<div class="page-banner">
    <div class="title"><span>Supported</span> Games</div>
</div>
<div class="page">
    <div class="container">
        <div class="page-inner">
            <div class="filter filterTitles">
                <div class="filter-01">
                    <div class="navTab">
                        <div class="navTab-inner">
                            @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                                <a class="@if($rule == '') active @endif" href="{{ @$url_action }}?prefix={{ $prefix }}&order={{ $order }}&friends={{ $friends }}&rule=&table={{ $table }}&table_rule={{ $table_rule }}">All Supported Games</a>
                                <a class="@if($rule == 'buildin') active @endif" href="{{ @$url_action }}?prefix={{ $prefix }}&order={{ $order }}&friends={{ $friends }}&rule=buildin&table={{ $table }}&table_rule={{ $table_rule }}">Built-in Games</a>
                                <a class="@if($rule == 'NOT') active @endif" href="{{ @$url_action }}?prefix={{ $prefix }}&order={{ $order }}&friends={{ $friends }}&rule=NOT&table={{ $table }}&table_rule={{ $table_rule }}">ArcadeNet®</a>
                                <a class="@if($rule == 'AND') active @endif" href="{{ @$url_action }}?prefix={{ $prefix }}&order={{ $order }}&friends={{ $friends }}&rule=AND&table={{ $table }}&table_rule={{ $table_rule }}">Pinball</a>
                            @else
                                <a class="@if($rule == '') active @endif" href="{!! action(@$url_action, array('prefix' => $prefix, 'order' => $order, 'friends' => $friends, 'rule' => '', 'table' => $table, 'table_rule' => $table_rule)) !!}">All Supported Games</a>
                                <a class="@if($rule == 'buildin') active @endif" href="{!! action(@$url_action, array('prefix' => $prefix, 'order' => $order, 'friends' => $friends, 'rule' => 'buildin', 'table' => $table, 'table_rule' => $table_rule)) !!}">Built-in Games</a>
                                <a class="@if($rule == 'NOT') active @endif" href="{!! action(@$url_action, array('prefix' => $prefix, 'order' => $order, 'friends' => $friends, 'rule' => 'NOT', 'table' => $table, 'table_rule' => $table_rule)) !!}">ArcadeNet®</a>
                                <a class="@if($rule == 'AND') active @endif" href="{!! action(@$url_action, array('prefix' => $prefix, 'order' => $order, 'friends' => $friends, 'rule' => 'AND', 'table' => $table, 'table_rule' => $table_rule)) !!}">Pinball</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="filter-02">
                    <div class="search">
                        @if(Request::root() != 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            <form method="GET" action="https://www.atgames.net/leaderboards/titles" accept-charset="UTF-8">
                        @else
                            {!! Form::open(array('url' => route('titles'), 'method' => 'get')) !!}
                        @endif
                        <div class="input-border">
                            {!! Form::text('keyword', $keyword, ['id' => 'keyword', 'class' => 'form-control', 'placeholder' => 'Search Game Title']) !!}
                        </div>
                        <button class="btn" type="submit" id="search">
                            <i class="icon-search" aria-hidden="true"></i>
                        </button>
                        {!! Form::hidden('rule', @$rule) !!}
                        {!! Form::hidden('prefix', @$prefix) !!}
                        {!! Form::hidden('order', @$order) !!}
                        {!! Form::hidden('friends', @$friends) !!}
                        {!! Form::hidden('table', @$table) !!}
                        {!! Form::hidden('table_rule', @$table_rule) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            @if(@$rule == 'AND')
            <div class="filter filterPinball">
                @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                    <div class="filter-01">
                        <a href="{{ @$url_action }}?prefix={{ $prefix }}&order={{ $order }}&friends={{ $friends }}&rule={{ $rule }}&table=buildin&table_rule=" class="btn btn-link @if($table == 'buildin') active @endif">
                            Built-in Tables
                        </a>
                    </div>
                    <div class="filter-02">
                        <span>Streaming Tables:</span>
                        <a href="{{ @$url_action }}?prefix={{ $prefix }}&order={{ $order }}&friends={{ $friends }}&rule={{ $rule }}&table=steam&table_rule=NOT" class="btn btn-link @if($table == 'steam' && $table_rule == 'NOT') active @endif">
                            Cloud
                        </a>
                        <a href="{{ @$url_action }}?prefix={{ $prefix }}&order={{ $order }}&friends={{ $friends }}&rule={{ $rule }}&table=steam&table_rule=AND" class="btn btn-link @if($table == 'steam' && $table_rule == 'AND') active @endif">
                            Steam
                        </a>
                        <a href="{{ @$url_action }}?prefix={{ $prefix }}&order={{ $order }}&friends={{ $friends }}&rule={{ $rule }}&table=steam&table_rule=all" class="btn btn-link @if($table == 'steam' && $table_rule == 'all') active @endif">
                            All
                        </a>
                    </div>
                @else
                    <div class="filter-01">
                        <a href="{!! action(@$url_action, array('prefix' => $prefix, 'order' => $order, 'friends' => $friends, 'rule' => $rule, 'table' => 'buildin', 'table_rule' => '')) !!}" class="btn btn-link @if($table == 'buildin') active @endif">
                            Built-in Tables
                        </a>
                    </div>
                    <div class="filter-02">
                        <span>Streaming Tables:</span>
                        <a href="{!! action(@$url_action, array('prefix' => $prefix, 'order' => $order, 'friends' => $friends, 'rule' => $rule, 'table' => 'steam', 'table_rule' => 'NOT')) !!}" class="btn btn-link @if($table == 'steam' && $table_rule == 'NOT') active @endif">
                            Cloud
                        </a>
                        <a href="{!! action(@$url_action, array('prefix' => $prefix, 'order' => $order, 'friends' => $friends, 'rule' => $rule, 'table' => 'steam', 'table_rule' => 'AND')) !!}" class="btn btn-link @if($table == 'steam' && $table_rule == 'AND') active @endif">
                            Steam
                        </a>
                        <a href="{!! action(@$url_action, array('prefix' => $prefix, 'order' => $order, 'friends' => $friends, 'rule' => $rule, 'table' => 'steam', 'table_rule' => 'all')) !!}" class="btn btn-link @if($table == 'steam' && $table_rule == 'all') active @endif">
                            All
                        </a>
                    </div>
                @endif
            </div>
            @endif
            <div class="filteraz">
                @include('Frontend.templates.filteraz')
            </div>
            <div class="filter filterSwitch">
                <div class="filter-01">
                    <div class="navLink">
                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            <a href="{{ @$url_action }}?prefix={{ $prefix }}&rule={{ $rule }}&table={{ $table }}&table_rule={{ $table_rule }}" class="btn btn-link @if($order == '' && $friends != 1) active @endif">
                                <i class="icon-an-all" aria-hidden="true"></i>
                                <span>All</span>
                            </a>
                            <a href="{{ @$url_action }}?prefix={{ $prefix }}&order=popular&rule={{ $rule }}&table={{ $table }}&table_rule={{ $table_rule }}" class="btn btn-link @if($order == 'popular') active @endif">
                                <i class="icon-an-fire" aria-hidden="true"></i>
                                <span>Popular</span>
                            </a>
                            <a href="{{ @$url_action }}?prefix={{ $prefix }}&order=latest&rule={{ $rule }}&table={{ $table }}&table_rule={{ $table_rule }}" class="btn btn-link @if($order == 'latest') active @endif">
                                <i class="icon-an-new" aria-hidden="true"></i>
                                <span>New</span>
                            </a>
                            @if(Session::has('token'))
                                <a href="{{ @$url_action }}?prefix={{ $prefix }}&friends=1&rule={{ $rule }}&table={{ $table }}&table_rule={{ $table_rule }}" class="btn btn-link @if($friends == 1) active @endif">
                                    <i class="icon-an-friends" aria-hidden="true"></i>
                                    <span>Friends</span>
                                </a>
                            @else
                                <a href="#" class="btn btn-link no-log-friends">
                                    <i class="icon-an-friends" aria-hidden="true"></i>
                                    <span>Friends</span>
                                </a>
                            @endif
                        @else
                            <a href="{!! action(@$url_action, array('prefix' => $prefix, 'rule' => $rule, 'table' => $table, 'table_rule' => $table_rule)) !!}" class="btn btn-link @if($order == '' && $friends != 1) active @endif">
                                <i class="icon-an-all" aria-hidden="true"></i>
                                <span>All</span>
                            </a>
                            <a href="{!! action(@$url_action, array('prefix' => $prefix, 'order' => 'popular', 'rule' => $rule, 'table' => $table, 'table_rule' => $table_rule)) !!}" class="btn btn-link @if($order == 'popular') active @endif">
                                <i class="icon-an-fire" aria-hidden="true"></i>
                                <span>Popular</span>
                            </a>
                            <a href="{!! action(@$url_action, array('prefix' => $prefix, 'order' => 'latest', 'rule' => $rule, 'table' => $table, 'table_rule' => $table_rule)) !!}" class="btn btn-link @if($order == 'latest') active @endif">
                                <i class="icon-an-new" aria-hidden="true"></i>
                                <span>New</span>
                            </a>
                            @if(Session::has('token'))
                                <a href="{!! action(@$url_action, array('prefix' => $prefix, 'friends' => 1, 'rule' => $rule, 'table' => $table, 'table_rule' => $table_rule)) !!}" class="btn btn-link @if($friends == 1) active @endif">
                                    <i class="icon-an-friends" aria-hidden="true"></i>
                                    <span>Friends</span>
                                </a>
                            @else
                                <a href="#" class="btn btn-link no-log-friends">
                                    <i class="icon-an-friends" aria-hidden="true"></i>
                                    <span>Friends</span>
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="filter-02">
                    <div class="navLink">
                        <button type="button" id="viewTitles-grid" class="btn btn-link active">
                            <span>Grid View</span>
                            <i class="icon-an-grid" aria-hidden="true"></i>
                        </button>
                        <button type="button" id="viewTitles-list" class="btn btn-link">
                            <span>List View</span>
                            <i class="icon-an-list" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="listTitles" id="listTitles">
                @foreach(@$title_data as $key => $value)
                <div class="item">
                    <div class="game">
                        <div class="game-border">
                            <div class="game-inner">
                                <div class="title">
                                    @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                                        <a href="https://www.atgames.net/leaderboards/device/top50/{{ @$value['game_id'] }}" title="Top 50 Scores">
                                    @else
                                        <a href="{!! action('Frontend\Device@pageDeviceTop50', array('game_id' => @$value['game_id'])) !!}" title="Top 50 Scores">
                                    @endif
                                        <span class="see-rank">{{ $key+1 }}</span>{!! @$value['name'] !!}
                                    </a>
                                </div>
                                <div class="cover">
                                    <div class="img-by img-by-5by2">
                                        <img src="{!! @$value['boxart'] !!}" alt="title">
                                    </div>
                                </div>
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
                {!! Form::hidden('last_game_id', @$last_game_id) !!}
                {!! Form::hidden('count', @$count) !!}
                {!! Form::hidden('rule', @$rule) !!}
                {!! Form::hidden('prefix', @$prefix) !!}
                {!! Form::hidden('order', @$order) !!}
                {!! Form::hidden('friends', @$friends) !!}
                {!! Form::hidden('table', @$table) !!}
                {!! Form::hidden('table_rule', @$table_rule) !!}
                {!! Form::hidden('keyword', @$keyword) !!}
            </div>
        </div>
        @if(count($title_data) == 8)
        <div class="loadMore">
            <button id="leaderboard_loadmore" type="button" class="btn btn-gradient">Load More</button>
        </div>
        @endif
        {!! Form::hidden('url', @action('CurlAPI@getTitleLeaderboardAfter')) !!}
    </div>
</div>
@stop

@section('Js')

@stop