@section('Nav')

<nav class="navbar navbar-expand-md">
    <div class="container">

        <button class="navbar-toggler navbar-toggler-close" id="navbarNavBtnX" type="button" data-toggle="collapse"
            data-target="#navbarNavDropdown" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="icon-x" aria-hidden="true"></i>
        </button>

        <button class="navbar-toggler" id="navbarNavBtn" type="button" data-toggle="collapse"
            data-target="#navbarNavDropdown" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="icon-menu" aria-hidden="true"></i>
        </button>

        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
            <a class="navbar-brand" href="https://www.atgames.net/leaderboards/" title="Home">
                <img src="{!! asset('/images/logo.png') !!}" alt="ArcadeNet">
            </a>
        @else
            <a class="navbar-brand" href="{!! action('Frontend\Index@pageIndex') !!}" title="Home">
                <img src="{!! asset('/images/logo.png') !!}" alt="ArcadeNet">
            </a>
        @endif
        
        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="nav navbar-nav">
                    <li class="nav-item @yield('Index')">
                        <a class="nav-link" href="https://www.atgames.net/leaderboards/">
                            <i class="icon-an-home" aria-hidden="true"></i>
                            Home
                        </a>
                    </li>
                    <li class="nav-item dropdown @yield('Device')">
                        <a class="nav-link dropdown-toggle" href="https://www.atgames.net/leaderboards/device">
                            <i class="icon-an-device" aria-hidden="true"></i>
                            Devices
                        </a>
                        <ul class="dropdown-menu">
                            @foreach(@$GLOBALS['model_list'] as $key => $value)
                                @switch($value['series_name'])
                                    @case('Ultimate')
                                        <li>
                                            <a class="dropdown-item" href="https://www.atgames.net/leaderboards/device?series={!! $value['series_id'] !!}" title="Legends {{ $value['series_name'] }}">
                                                <i class="icon-an-ultimate" aria-hidden="true"></i>
                                                Legends {{ $value['series_name'] }}
                                            </a>
                                        </li>
                                        @break
                                    @case('Gamer')
                                        <li>
                                            <a class="dropdown-item" href="https://www.atgames.net/leaderboards/device?series={!! $value['series_id'] !!}" title="Legends {{ $value['series_name'] }}">
                                                <i class="icon-an-gamer" aria-hidden="true"></i>
                                                Legends {{ $value['series_name'] }}
                                            </a>
                                        </li>
                                        @break
                                    @case('Flashback')
                                        <li>
                                            <a class="dropdown-item" href="https://www.atgames.net/leaderboards/device?series={!! $value['series_id'] !!}" title="Legends {{ $value['series_name'] }}">
                                                <i class="icon-an-flashback" aria-hidden="true"></i>
                                                Legends {{ $value['series_name'] }}
                                            </a>
                                        </li>
                                        @break
                                    @case('Pinball')
                                        <li>
                                            <a class="dropdown-item" href="https://www.atgames.net/leaderboards/device?series={!! $value['series_id'] !!}" title="Legends {{ $value['series_name'] }}">
                                                <i class="icon-an-pinball" aria-hidden="true"></i>
                                                Legends {{ $value['series_name'] }}
                                            </a>
                                        </li>
                                        @break
                                    @case('Connect')
                                        <li>
                                            <a class="dropdown-item" href="https://www.atgames.net/leaderboards/device?series={!! $value['series_id'] !!}" title="Legends {{ $value['series_name'] }}">
                                                <i class="icon-an-connect" aria-hidden="true"></i>
                                                Legends {{ $value['series_name'] }}
                                            </a>
                                        </li>
                                        @break
                                    @case('Core')
                                        <li>
                                            <a class="dropdown-item" href="https://www.atgames.net/leaderboards/device?series={!! $value['series_id'] !!}" title="Legends {{ $value['series_name'] }}">
                                                <i class="icon-an-core" aria-hidden="true"></i>
                                                Legends {{ $value['series_name'] }}
                                            </a>
                                        </li>
                                        @break
                                    @default
                                        @break
                                @endswitch
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item dropdown @yield('Titles')">
                        <a class="nav-link dropdown-toggle" href="https://www.atgames.net/leaderboards/titles">
                            <i class="icon-an-cartridge" aria-hidden="true"></i>
                            Titles
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="https://www.atgames.net/leaderboards/titles" title="All Supported Games">
                                    <i class="icon-an-ship" aria-hidden="true"></i>
                                    All Supported Games
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="https://www.atgames.net/leaderboards/titles?rule=buildin" title="ArcadeNet">
                                    <i class="icon-an-joystick" aria-hidden="true"></i>
                                    Built-in Games
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="https://www.atgames.net/leaderboards/titles?rule=NOT" title="ArcadeNet">
                                    <i class="icon-an-joystick" aria-hidden="true"></i>
                                    ArcadeNet®
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="https://www.atgames.net/leaderboards/titles?rule=AND" title="Pinball">
                                    <i class="icon-an-pinball" aria-hidden="true"></i>
                                    Pinball
                                </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="https://www.atgames.net/leaderboards/titles?rule=AND&table=buildin&table_rule=" title="Built-In Tables">
                                            Built-in Tables
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.atgames.net/leaderboards/titles?rule=AND&table=steam&table_rule=NOT" title="Streaming Tables">
                                            Streaming Tables
                                        </a>
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="https://www.atgames.net/leaderboards/titles?rule=AND&table=steam&table_rule=NOT" title="Cloud">
                                                    Cloud
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.atgames.net/leaderboards/titles?rule=AND&table=steam&table_rule=AND" title="Steam">
                                                    Steam
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.atgames.net/leaderboards/titles?rule=AND&table=steam&table_rule=all" title="All">
                                                    All
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item @yield('Schedule')">
                        <a class="nav-link" href="https://www.atgames.net/leaderboards/schedule">
                            <i class="icon-an-calendar" aria-hidden="true"></i>
                            Schedule
                        </a>
                    </li>
                    <li class="nav-item dropdown @yield('Family')">
                        <a class="nav-link dropdown-toggle" href="https://www.atgames.net/arcades">
                            <i class="icon-an-joysticks" aria-hidden="true"></i>
                            AtGames Family
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="https://www.atgames.net/arcades/legends-ultimate/" title="Legends Ultimate">
                                    <i class="icon-an-ultimate" aria-hidden="true"></i>
                                    Legends Ultimate
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="https://www.atgames.net/arcades/legends-gamer-series/" title="Legends Gamer Series">
                                    <i class="icon-an-gamer" aria-hidden="true"></i>
                                    Legends Gamer Series
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="https://www.atgames.net/arcades/legends-core/" title="Legends Core">
                                    <i class="icon-an-core" aria-hidden="true"></i>
                                    Legends Core
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="https://www.atgames.net/arcades/legends-connect/" title="Legends Connect">
                                    <i class="icon-an-connect" aria-hidden="true"></i>
                                    Legends Connect
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="https://www.atgames.net/arcades/legends-pinball/" title="Legends Pinball">
                                    <i class="icon-an-pinball" aria-hidden="true"></i>
                                    Legends Pinball
                                </a>
                            </li>
                        </ul>
                    </li>
                    @if(Session::has('token'))
                        <li class="nav-item dropdown @yield('Login')">
                            <a class="nav-link dropdown-toggle" href="#">
                                <i class="icon-an-joystick" aria-hidden="true"></i>
                                {{ Session::get('userName') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item logout-btn" href="#" title="Logout">
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item @yield('Login')">
                            <a class="nav-link" href="#loginModal" data-toggle="modal">
                                <i class="icon-an-joystick" aria-hidden="true"></i>
                                Login/Sign Up
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        @else
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="nav navbar-nav">
                    <li class="nav-item @yield('Index')">
                        <a class="nav-link" href="{!! action('Frontend\Index@pageIndex') !!}">
                            <i class="icon-an-home" aria-hidden="true"></i>
                            Home
                        </a>
                    </li>
                    <li class="nav-item dropdown @yield('Device')">
                        <a class="nav-link dropdown-toggle" href="{!! action('Frontend\Device@pageDevice') !!}">
                            <i class="icon-an-device" aria-hidden="true"></i>
                            Devices
                        </a>
                        <ul class="dropdown-menu">
                            @foreach(@$GLOBALS['model_list'] as $key => $value)
                                @switch($value['series_name'])
                                    @case('Ultimate')
                                        <li>
                                            <a class="dropdown-item" href="{!! action('Frontend\Device@pageDevice', array('series' => $value['series_id'])) !!}" title="Legends {{ $value['series_name'] }}">
                                                <i class="icon-an-ultimate" aria-hidden="true"></i>
                                                Legends {{ $value['series_name'] }}
                                            </a>
                                        </li>
                                        @break
                                    @case('Gamer')
                                        <li>
                                            <a class="dropdown-item" href="{!! action('Frontend\Device@pageDevice', array('series' => $value['series_id'])) !!}" title="Legends {{ $value['series_name'] }}">
                                                <i class="icon-an-gamer" aria-hidden="true"></i>
                                                Legends {{ $value['series_name'] }}
                                            </a>
                                        </li>
                                        @break
                                    @case('Flashback')
                                        <li>
                                            <a class="dropdown-item" href="{!! action('Frontend\Device@pageDevice', array('series' => $value['series_id'])) !!}" title="Legends {{ $value['series_name'] }}">
                                                <i class="icon-an-flashback" aria-hidden="true"></i>
                                                Legends {{ $value['series_name'] }}
                                            </a>
                                        </li>
                                        @break
                                    @case('Pinball')
                                        <li>
                                            <a class="dropdown-item" href="{!! action('Frontend\Device@pageDevice', array('series' => $value['series_id'])) !!}" title="Legends {{ $value['series_name'] }}">
                                                <i class="icon-an-pinball" aria-hidden="true"></i>
                                                Legends {{ $value['series_name'] }}
                                            </a>
                                        </li>
                                        @break
                                    @case('Connect')
                                        <li>
                                            <a class="dropdown-item" href="{!! action('Frontend\Device@pageDevice', array('series' => $value['series_id'])) !!}" title="Legends {{ $value['series_name'] }}">
                                                <i class="icon-an-connect" aria-hidden="true"></i>
                                                Legends {{ $value['series_name'] }}
                                            </a>
                                        </li>
                                        @break
                                    @case('Core')
                                        <li>
                                            <a class="dropdown-item" href="{!! action('Frontend\Device@pageDevice', array('series' => $value['series_id'])) !!}" title="Legends {{ $value['series_name'] }}">
                                                <i class="icon-an-core" aria-hidden="true"></i>
                                                Legends {{ $value['series_name'] }}
                                            </a>
                                        </li>
                                        @break
                                    @default
                                        @break
                                @endswitch
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item dropdown @yield('Titles')">
                        <a class="nav-link dropdown-toggle" href="{!! action('Frontend\Titles@pageTitles') !!}">
                            <i class="icon-an-cartridge" aria-hidden="true"></i>
                            Titles
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{!! action('Frontend\Titles@pageTitles') !!}" title="All Supported Games">
                                    <i class="icon-an-ship" aria-hidden="true"></i>
                                    All Supported Games
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{!! action('Frontend\Titles@pageTitles', array('rule' => 'buildin')) !!}" title="ArcadeNet">
                                    <i class="icon-an-joystick" aria-hidden="true"></i>
                                    Built-in Games
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{!! action('Frontend\Titles@pageTitles', array('rule' => 'NOT')) !!}" title="ArcadeNet">
                                    <i class="icon-an-joystick" aria-hidden="true"></i>
                                    ArcadeNet®
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{!! action('Frontend\Titles@pageTitles', array('rule' => 'AND')) !!}" title="Pinball">
                                    <i class="icon-an-pinball" aria-hidden="true"></i>
                                    Pinball
                                </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{!! action('Frontend\Titles@pageTitles', array('rule' => 'AND', 'table' => 'steam', 'table_rule' => '')) !!}" title="Built-In Tables">
                                            Built-in Tables
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{!! action('Frontend\Titles@pageTitles', array('rule' => 'AND', 'table' => 'steam', 'table_rule' => 'NOT')) !!}" title="Streaming Tables">
                                            Streaming Tables
                                        </a>
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="{!! action('Frontend\Titles@pageTitles', array('rule' => 'AND', 'table' => 'steam', 'table_rule' => 'NOT')) !!}" title="Cloud">
                                                    Cloud
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{!! action('Frontend\Titles@pageTitles', array('rule' => 'AND', 'table' => 'steam', 'table_rule' => 'AND')) !!}" title="Steam">
                                                    Steam
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{!! action('Frontend\Titles@pageTitles', array('rule' => 'AND', 'table' => 'steam', 'table_rule' => 'all')) !!}" title="All">
                                                    All
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item @yield('Schedule')">
                        <a class="nav-link" href="{!! action('Frontend\Schedule@pageSchedule') !!}">
                            <i class="icon-an-calendar" aria-hidden="true"></i>
                            Schedule
                        </a>
                    </li>
                    <li class="nav-item dropdown @yield('Family')">
                        <a class="nav-link dropdown-toggle" href="https://www.atgames.net/arcades">
                            <i class="icon-an-joysticks" aria-hidden="true"></i>
                            AtGames Family
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="https://www.atgames.net/arcades/legends-ultimate/" title="Legends Ultimate">
                                    <i class="icon-an-ultimate" aria-hidden="true"></i>
                                    Legends Ultimate
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="https://www.atgames.net/arcades/legends-gamer-series/" title="Legends Gamer Series">
                                    <i class="icon-an-gamer" aria-hidden="true"></i>
                                    Legends Gamer Series
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="https://www.atgames.net/arcades/legends-core/" title="Legends Core">
                                    <i class="icon-an-core" aria-hidden="true"></i>
                                    Legends Core
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="https://www.atgames.net/arcades/legends-connect/" title="Legends Connect">
                                    <i class="icon-an-connect" aria-hidden="true"></i>
                                    Legends Connect
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="https://www.atgames.net/arcades/legends-pinball/" title="Legends Pinball">
                                    <i class="icon-an-pinball" aria-hidden="true"></i>
                                    Legends Pinball
                                </a>
                            </li>
                        </ul>
                    </li>
                    @if(Session::has('token'))
                        <li class="nav-item dropdown @yield('Login')">
                            <a class="nav-link dropdown-toggle" href="#">
                                <i class="icon-an-joystick" aria-hidden="true"></i>
                                {{ Session::get('userName') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item logout-btn" href="#" title="Logout">
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item @yield('Login')">
                            <a class="nav-link" href="#loginModal" data-toggle="modal">
                                <i class="icon-an-joystick" aria-hidden="true"></i>
                                Login/Sign Up
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
    </div>
</nav>

@stop