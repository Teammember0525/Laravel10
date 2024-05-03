<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>@yield('Title') - ArcadeNet</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{!! asset('/images/favicon.png') !!}">

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
        integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous">
        </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
        integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous">
        </script> <!-- 影響Calendar -->
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery.smartmenus/1.1.1/jquery.smartmenus.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery.smartmenus/1.1.1/addons/bootstrap-4/jquery.smartmenus.bootstrap-4.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery.smartmenus/1.1.1/addons/keyboard/jquery.smartmenus.keyboard.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js"></script>

    <script src="{!! asset('/js/script.js') !!}"></script>
    <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-pageLoadMore/1.0.0/js/dataTables.pageLoadMore.min.js"></script>
    <script src="https://cdn.staticfile.org/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script type="text/javascript">
        var base_path = "{!! url('/', []) !!}";
        var host_url = window.location.hostname;
        if(host_url == 'acnet-lb.atgames.net'){
            window.location.href = 'https://www.atgames.net/leaderboards/';
        }

        $(document).on('click', '.login-btn', function(){
            let email = $('input[name=email]').val();
            let password = $('input[name=password]').val();

            var host_name = window.location.hostname;
            if(host_name == 'www.atgames.net' || host_name == 'acnet-lb.atgames.net'){
                var login_url = 'https://www.atgames.net/leaderboards/login';
            }else{
                var login_url = `{!! url('/') !!}/login`;
            }

            $.ajax({
                type: 'post',
                url: login_url,
                data: {
                    'email' : email,
                    'password' : password,
                },
                // async: false,
                success: function(e){
                    login_data = JSON.parse(e);

                    if(login_data.status != 500){
                        let uuid = login_data.response.account.uuid;
                        let userName = login_data.response.account.userName;
                        let socialUserName = login_data.response.account.socialUserName;
                        let token = login_data.response.account.token;

                        if(host_name == 'www.atgames.net' || host_name == 'acnet-lb.atgames.net'){
                            $.cookie('uuid', uuid, { expires: 1, path: '/leaderboards/' });
                            $.cookie('userName', userName, { expires: 1, path: '/leaderboards/' });
                            $.cookie('socialUserName', socialUserName, { expires: 1, path: '/leaderboards/' });
                            $.cookie('token', token, { expires: 1, path: '/leaderboards/' });
                        }else{
                            $.cookie('uuid', uuid, { expires: 1, path: '/' });
                            $.cookie('userName', userName, { expires: 1, path: '/' });
                            $.cookie('socialUserName', socialUserName, { expires: 1, path: '/' });
                            $.cookie('token', token, { expires: 1, path: '/' });
                        }

                        setTimeout(function() {
                            location.reload();
                        }, 1);
                    }else{
                        console.log(login_data);
                        alert(login_data.response.failureError.message);

                        // setTimeout(function() {
                        //     location.reload();
                        // }, 3);
                    }
                },
            });
        })

        $(document).on('click', '.logout-btn', function(){

            var host_name = window.location.hostname;
            if(host_name == 'www.atgames.net' || host_name == 'acnet-lb.atgames.net'){
                var login_url = 'https://www.atgames.net/leaderboards/logout';
            }else{
                var login_url = `{!! url('/') !!}/logout`;
            }

            $.ajax({
                type: 'get',
                url: login_url,
                success: function(e){

                    if(host_name == 'www.atgames.net' || host_name == 'acnet-lb.atgames.net'){
                        $.removeCookie('uuid', { path: '/leaderboards/' });
                        $.removeCookie('userName', { path: '/leaderboards/' });
                        $.removeCookie('socialUserName', { path: '/leaderboards/' });
                        $.removeCookie('token', { path: '/leaderboards/' });
                    }else{
                        $.removeCookie('uuid', { path: '/' });
                        $.removeCookie('userName', { path: '/' });
                        $.removeCookie('socialUserName', { path: '/' });
                        $.removeCookie('token', { path: '/' });
                    }

                    setTimeout(function() {
                        location.reload();
                    }, 1);
                },
            });
        })

        $(document).ready(function() {
            // show the alert
            setTimeout(function() {
                $(".alert-login-msg").alert('close');
            }, 5000);
        });
    </script>
    @yield('HeadContent')

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <link rel="stylesheet" href="{!! asset('/fonts/icomoon/style.css') !!}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700;800&display=swap">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/tiny-slider.css">
    <!--[if (lt IE 9)]><script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.helper.ie8.js"></script><![endif]-->
    <link rel="stylesheet" href="{!! asset('/css/style.css') !!}">
</head>

<body>
    <div class="wrapper" id="top">
        <header class="header">
            @yield('Nav')
            @if($errors->any())
                {{-- <div class="alert alert-danger alert-dismissible alert-login-msg">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>{{$errors->first()}}</strong>
                </div> --}}
            @endif
        </header>
        <section class="main">
            @yield('Content')
        </section>
        <footer class="footer">
            @yield('Footer')
        </footer>
    </div>
    <!-- Modal Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <div>close</div>
            </button>
            <div class="modal-content">
                <div class="login">
                    <div class="login-header">
                        <h5 class="modal-title" id="loginModalLabel">Log In to get alerts!</h5>
                        <p>You must Log into your account to get alerts or <a href="https://www.atgames.net/arcadenet/auth/sign-up" class="link-underline">create an
                                account.</a></p>
                    </div>
                    <form class="form-sp">
                        <div class="form-group">
                            <label for="name">User Name</label>
                            <div class="input-border">
                                {!! Form::text('email', '', ['id' => 'email', 'class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-border">
                                {!! Form::password('password',array('id'=>'password','class'=>'form-control','placeholder'=>'')) !!}
                            </div>
                        </div>
                        <button type="button" class="btn btn-gradient login-btn">Submit</button>
                        <a href="https://www.atgames.net/arcadenet/auth/forget-password" class="link-underline forgot">Forgot Password</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @yield('Js')
</body>

</html>
