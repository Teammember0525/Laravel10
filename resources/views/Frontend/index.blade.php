@extends('Frontend.templates.layout')
@extends('Frontend.templates.nav')
@extends('Frontend.templates.footer')

@section('Title','Home')
@section('Index','active')

@section('HeadContent')
<script>
    $(document).ready(function () {
        var slider = tns({
            container: '#sliderEvents',
            controls: true, //上下則
            controlsContainer: "#controlsEvents",
            nav: true, //滑點
            mouseDrag: true, //拖動更改幻燈片
            autoplay: true, //自動播放
            autoplayButtonOutput: false, //自動播放按鈕顯示
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

        $('.events').on('click', function(){
            name = $(this).find('[name=name]').val();
            start = $(this).find('[name=start]').val();
            end = $(this).find('[name=end]').val();
            
            $('.events').removeClass('active');
            $(this).addClass('active');
            $('#caption-title').text(name);
            $('#caption-time').text(start + ' - ' + end);
        })
    });
</script>
@stop

@section('Content')
<section class="index-banner">
    <div class="index-banner-inner">
        <div class="caption">
            <div class="caption-header">Join the Fun!</div>
            @foreach (@$data['tournaments'] as $key2 => $value2)
                @if($key2 == 0)
                    <div id="caption-title" class="caption-title">{{ $value2['name'] }}</div>
                    <div id="caption-time" class="caption-time">{{ $value2['start'] }} - {{ $value2['end'] }}</div>
                @endif
            @endforeach
        </div>
    </div>
</section>
<section class="index-news">
    <div class="container">
        <div class="index-news-inner">
            @if(@$count > 0)
            <div class="index-events">
                <h2 class="block-title">Upcoming Events</h2>
                <div id="sliderEvents">
                    @foreach (@$data['tournaments'] as $key => $value)
                        <div class="events item @if($key == 0) active @endif">
                            {!! Form::hidden('name', @$value['name']) !!}
                            {!! Form::hidden('start', @$value['start']) !!}
                            {!! Form::hidden('end', @$value['end']) !!}
                            <a href="#" title="title" class="item-link">
                                <div class="cover">
                                    <div class="img-by img-by-5by2">
                                        <img src="{!! $value['overlay'] !!}" alt="title">
                                    </div>
                                </div>
                                <div class="caption">
                                    <div class="date">
                                        <span>{{ $value['start'] }}</span><span>-</span><span>{{ $value['end'] }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div id="controlsEvents">
                    <button class="btn-controls btn-controls-left" type="button">
                        <i class="icon-chevron-left" aria-hidden="true"></i>
                    </button>
                    <button class="btn-controls btn-controls-right" type="button">
                        <i class="icon-chevron-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            @endif
            <div class="index-about">
                <h2 class="block-title">About Us</h2>
                <p>
                    AtGames Leagues Leaderboards™ (ALL) is the premiere even and highscore tracking destination
                    for the
                    Legends connected home arcade series of products, including the Legends Ultimate and Legends Gamer.
                    You
                    can
                    see active and upcoming solo and team-based game events, as well as the top ranking - and where you
                    place -
                    for a growing selection of legendary games.
                </p>
            </div>
        </div>
    </div>
</section>
<section class="index-join">
    <div class="container">
        <div class="index-join-text">
            <h2 class="block-title block-title-w">
                <span>How to join the leaderboard</span>
                Legends Ultimate/gamer
            </h2>
            <ul class="list-check">
                <li>Start a game that shows a trophy icon over the box art.</li>
                <li>Play until you achieve a high score. Press the <strong>“MENU”</strong> button to bring up the
                    in-game menu.</li>
                <li>Select <strong>“SAVE HIGH SCORE”</strong> to have your score recognized by the system.</li>
            </ul>
        </div>
    </div>
    <div class="index-join-pic">
        <img src="{!! asset('/images/index-join-pic.png') !!}" alt="How to join the leaderboard">
    </div>
</section>
<section class="index-flashback">
    <div class="container">
        <div class="index-flashback-text">
            <h2 class="block-title block-title-w">Legends Flashback</h2>
            <ul class="list-check">
                <li>Start a game that shows a trophy icon over the box art</li>
                <li>Play until you achieve a high score. Press the <strong>“MENU”</strong> button to bring up the
                    in-game menu.</li>
                <li>Select <strong>“SAVE HIGH SCORE”</strong> to have your score recognized by the system</li>
                <li>Enter the initials you would like displayed with your score</li>
                <li>Use your mobile device to scan the QR code on the screen</li>
                <li>Sign into <strong>“ArcadeNet”</strong> go to <strong>“My High Scores”</strong></li>
                <li>Click the “Upload Score” button then click “Get Code”</li>
                <li>Enter the 3 letter verification code from your mobile device on your console</li>
                <li>Use your mobile device to scan the QR code to complete the score submission</li>
                <li>You will receive a confirmation message on your mobile device</li>
                <li>Check the results on <strong>“AtGames Legues Leaderboards”</strong></li>
            </ul>
        </div>
        <div class="index-flashback-pic">
            <img src="{!! asset('/images/index-flashback-pic.png') !!}" alt="How to join the leaderboard">
        </div>
    </div>
</section>
<section class="index-bg">
    <img src="{!! asset('/images/index-bg.jpg') !!}" alt="">
</section>
@stop

@section('Js')

@stop