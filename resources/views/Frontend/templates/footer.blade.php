@section('Footer')
<div class="container">
    <img src="{!! asset('/images/logo.png') !!}" alt="ArcadeNet" class="footer-logo">
    <ul class="footer-link">
        <li>
            @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                <a href="https://www.atgames.net/leaderboards/" title="Home">Home</a>
            @else
                <a href="{!! action('Frontend\Index@pageIndex') !!}" title="Home">Home</a>
            @endif
        </li>
        <li>
            <a href="https://www.atgames.net/arcadenet/about/privacy-policy" title="Privacy Policy">Privacy Policy</a>
        </li>
        <li>
            <a href="https://www.atgames.net/arcadenet/about/terms-of-use" title="Terms of Use">Terms of Use</a>
        </li>
        <li>
            <a href="https://www.atgames.net/" title="Corporate">Corporate</a>
        </li>
    </ul>
    <div class="footer-copyright">Copyright: AtGames Cloud Holdings Ltd. All rights reserved. All supported gaming
        platforms and trademarks are
        properties of their respective owners.</div>
</div>
@stop