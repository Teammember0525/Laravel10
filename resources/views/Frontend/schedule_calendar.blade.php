@extends('Frontend.templates.layout')
@extends('Frontend.templates.nav')
@extends('Frontend.templates.footer')

@section('Title','Schedule')
@section('Schedule','active')

@section('HeadContent')
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    $(document).ready(function () {
        var getImg_data = '';
        var teamName = '';

        var month_to_en = new Array(12);
            month_to_en[1] = "January";
            month_to_en[2] = "February";
            month_to_en[3] = "March";
            month_to_en[4] = "April";
            month_to_en[5] = "May";
            month_to_en[6] = "June";
            month_to_en[7] = "July";
            month_to_en[8] = "August";
            month_to_en[9] = "Septemper";
            month_to_en[10] = "October";
            month_to_en[11] = "November";
            month_to_en[12] = "December";

        var month_to_number = new Array(12);
            month_to_number['January'] = 1;
            month_to_number['February'] = 2;
            month_to_number['March'] = 3;
            month_to_number['April'] = 4;
            month_to_number['May'] = 5;
            month_to_number['June'] = 6;
            month_to_number['July'] = 7;
            month_to_number['August'] = 8;
            month_to_number['Septemper'] = 9;
            month_to_number['October'] = 10;
            month_to_number['November'] = 11;
            month_to_number['December'] = 12;

        var encode_data = `{!! $tournaments_data !!}`;
        var data = JSON.parse(encode_data);

        jQuery(function () {
            // page is ready
            jQuery('#calendar').fullCalendar({
                themeSystem: 'bootstrap4',
                // emphasizes business hours
                businessHours: false,
                defaultView: 'month',
                // event dragging & resizing
                editable: false,
                // header
                header: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                events: data,
                dayClick: function () {
                    jQuery('#modal-view-event-add').modal();
                },
                eventClick: function (event, jsEvent, view) {
                    jQuery('.event-icon').html("<i class='fa fa-" + event.icon + "'></i>");
                    jQuery('.event-title').html(event.title);
                    jQuery('.event-body').html(event.description);
                    jQuery('.eventUrl').attr('href', event.url);

                    // console.log(event);
                    $('.modal-calendar').removeClass('modal-calendar-active modal-calendar-upcoming modal-calendar-expired');
                    $('.modal-calendar').addClass('modal-calendar-'+event.status);
                    jQuery('.eventHeader-titleSub').html(event.title);
                    jQuery('.eventHeader-date').html('<strong>Event date: </strong>'+event.modal_start+' through '+event.modal_end);
                    jQuery('.eventInfo-title').html(event.title);
                    jQuery('.eventInfo-title-description').html(event.description);

                    $('.img-slider').empty();
                    $('.eventInfo-btn').empty();

                    getImg(event.event_id);
                    $.each(getImg_data.games, function(key, value){
                        var option = 
                            `<div class="item">
                                <div class="game">
                                    <div class="game-border">
                                        <div class="game-inner">
                                            <div class="title"><span>${key+1}</span>${value.name}</div>
                                            <div class="cover">
                                                <div class="img-by img-by-5by2">
                                                    <img src="${value.boxart}" alt="title">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        $('.img-slider').append(option);
                    })

                    if(event.status == 'active'){
                        var option2 = 
                            `<button type="button" class="btn btn-gradient open-bracketModal">View bracket</button>
                            <div class="status event-status">

                            </div>`;

                        $('.eventInfo-btn').append(option2);
                    }else if(event.status == 'upcoming'){
                        var option2 = 
                            `<button type="button" class="btn btn-gradient open-enterModal">Enter Tournament</button>
                            <div class="status event-status">

                            </div>`;

                        $('.eventInfo-btn').append(option2);
                    }else{
                        var option2 = 
                            `<div class="status event-status">

                            </div>`;

                        $('.eventInfo-btn').append(option2);
                    }

                    $('.event-status').removeClass('status-active status-upcoming status-expired');
                    $('.event-status').addClass('status-'+event.status);
                    $('.event-status').html('<i class="icon-an-'+event.status+'" aria-hidden="true"></i>'+event.big_status);

                    if(event.style == 'bracket'){
                        var host_name = window.location.hostname;
                        if(host_name == 'www.atgames.net' || host_name == 'acnet-lb.atgames.net'){
                            $(".icon-src").attr("src","https://www.atgames.net/leaderboards/images/event-tournament.svg");
                        }else{
                            $(".icon-src").attr("src","/images/event-tournament.svg");
                        }
                    }
                    jQuery('#calendarModal').modal();

                    var slider = tns({
                        container: '#sliderGame',
                        controls: false, //上下則
                        nav: true, //滑點
                        navPosition: "bottom",
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
                        }
                    });

                    let event_id = event.event_id;
                    let eventConfigUrl = event.eventConfigUrl;

                    // enter
                    $('.open-enterModal').on('click', function(){
                        $('#calendarModal').modal('hide');
                        
                        var socialUserName = '{!! Session::get('socialUserName') !!}';
                        var had_login = '{!! $had_login !!}';
                        if(had_login){

                            let p_data = viewEventConfigData(event_id)

                            p_data.finally( val => {
                                
                            })

                            viewEventConfigData(event_id).then ( res => {
                                
                                var max_team_size = res.a.data.max_team_size;
                                var team_info = res.b;

                                if(team_info != 500){
                                    var own_team_info = team_info.data;
                                    if(own_team_info.status == 404 || own_team_info.status == 500){
                                        own_team_info = '';
                                    }
                                }else{
                                    own_team_info = '';
                                }

                                $('.form-group').remove();
                                $('.enterModal-submit').remove();

                                if(own_team_info){
                                    console.log('have_team');
                                    var leader_uuid = own_team_info.leader_uuid;
                                    var team_name = own_team_info.team_name;
                                    var enter1 = `<input type="hidden" name="form_event_id" value="${event_id}">
                                                    <div class="form-group">
                                                        <label for="name">Team Name</label>
                                                        <div class="input-border">
                                                            <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp" placeholder="" value="${team_name}">
                                                        </div>
                                                    </div>`;

                                    if(max_team_size != -1){
                                        max_team_size = max_team_size - 1;

                                        if(own_team_info.members.length - 1 == 0){
                                            // Leader
                                            $.each(own_team_info.members, function(key, value){
                                                var new_key = key+1;
                                                if(new_key == 1){
                                                    enter1 += `<div class="form-group">
                                                                    <label for="friend${new_key}">Leader</label>
                                                                    <div class="input-border">
                                                                        <input type="text" class="form-control" id="friend${new_key}" name="leader[]" aria-describedby="emailHelp" placeholder="Team Member ID" value="${value.username}" disabled="disabled">
                                                                    </div>
                                                                </div>`;
                                                }
                                            })
                                            // Add Friends
                                            for($h=1; $h<= max_team_size; $h++){
                                                enter1 += `<div class="form-group">
                                                                <label for="friend"${$h}>Add Friends</label>
                                                                <div class="input-border">
                                                                    <input type="text" class="form-control" id="friend${$h}" name="friend[]" aria-describedby="emailHelp" placeholder="Team Member ID" value="">
                                                                </div>
                                                            </div>`;
                                            }
                                        }else{
                                            // Leader & Friends
                                            $.each(own_team_info.members, function(key, value){
                                                console.log(own_team_info.members.length);
                                                var new_key = key+1;
                                                if(new_key == 1){
                                                    enter1 += `<div class="form-group">
                                                                    <label for="friend${new_key}">Leader</label>
                                                                    <div class="input-border">
                                                                        <input type="text" class="form-control" id="friend${new_key}" name="leader[]" aria-describedby="emailHelp" placeholder="Team Member ID" value="${value.username}" disabled="disabled">
                                                                    </div>
                                                                </div>`;
                                                }else{
                                                    // for($j=1; $j<= max_team_size-1; $j++){
                                                        enter1 += `<div class="form-group">
                                                                        <label for="friend${new_key}">Friends</label>
                                                                        <div class="input-border">
                                                                            <input type="text" class="form-control" id="friend${new_key}" name="friend[]" aria-describedby="emailHelp" placeholder="Team Member ID" value="${value.username}" disabled="disabled">
                                                                        </div>
                                                                    </div>`;
                                                    // }
                                                }
                                            })
                                            var for_long = max_team_size - (own_team_info.members.length - 1);
                                            // Add Friends
                                            for($h=1; $h<= for_long; $h++){
                                                enter1 += `<div class="form-group">
                                                                <label for="friend"${$h}>Add Friends</label>
                                                                <div class="input-border">
                                                                    <input type="text" class="form-control" id="friend${$h}" name="friend[]" aria-describedby="emailHelp" placeholder="Team Member ID" value="">
                                                                </div>
                                                            </div>`;
                                            }
                                        }
                                    }else{
                                        if(own_team_info.members.length - 1 == 0){
                                            // Leader
                                            $.each(own_team_info.members, function(key, value){
                                                let new_key = key+1;
                                                if(new_key == 1){
                                                    enter1 += `<div class="form-group">
                                                                    <label for="friend${new_key}">Leader</label>
                                                                    <div class="input-border">
                                                                        <input type="text" class="form-control" id="friend${new_key}" name="leader[]" aria-describedby="emailHelp" placeholder="Team Member ID" value="${value.username}" disabled="disabled">
                                                                    </div>
                                                                </div>`;
                                                }
                                            })
                                            // Add Friends
                                            for($h=1; $h<= 5; $h++){
                                                enter1 += `<div class="form-group">
                                                                <label for="friend"${$h}>Add Friends</label>
                                                                <div class="input-border">
                                                                    <input type="text" class="form-control" id="friend${$h}" name="friend[]" aria-describedby="emailHelp" placeholder="Team Member ID" value="">
                                                                </div>
                                                            </div>`;
                                            }
                                        }else{
                                            // Leader & Friends
                                            $.each(own_team_info.members, function(key, value){
                                                let new_key = key+1;
                                                if(new_key == 1){
                                                    enter1 += `<div class="form-group">
                                                                    <label for="friend${new_key}">Leader</label>
                                                                    <div class="input-border">
                                                                        <input type="text" class="form-control" id="friend${new_key}" name="leader[]" aria-describedby="emailHelp" placeholder="Team Member ID" value="${value.username}" disabled="disabled">
                                                                    </div>
                                                                </div>`;
                                                }else{
                                                    // for($j=1; $j<= max_team_size; $j++){
                                                        enter1 += `<div class="form-group">
                                                                        <label for="friend${new_key}">Friends</label>
                                                                        <div class="input-border">
                                                                            <input type="text" class="form-control" id="friend${new_key}" name="friend[]" aria-describedby="emailHelp" placeholder="Team Member ID" value="${value.username}" disabled="disabled">
                                                                        </div>
                                                                    </div>`;
                                                    // }
                                                }
                                            })
                                            // Add Friends
                                            for($h=1; $h<= 5; $h++){
                                                enter1 += `<div class="form-group">
                                                                <label for="friend"${$h}>Add Friends</label>
                                                                <div class="input-border">
                                                                    <input type="text" class="form-control" id="friend${$h}" name="friend[]" aria-describedby="emailHelp" placeholder="Team Member ID" value="">
                                                                </div>
                                                            </div>`;
                                            }
                                        }
                                    }
                                }else{
                                    console.log('no_team');
                                    var team_name = '';
                                    var enter1 = `<input type="hidden" name="form_event_id" value="${event_id}">
                                                    <div class="form-group">
                                                        <label for="name">Team Name</label>
                                                        <div class="input-border">
                                                            <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp" placeholder="" value="${team_name}">
                                                        </div>
                                                    </div>`;
                                    if(max_team_size != -1){
                                        max_team_size = max_team_size - 1;
                                        for($j=1; $j<= max_team_size; $j++){
                                            enter1 += `<div class="form-group">
                                                            <label for="friend${$j}">Add Friends</label>
                                                            <div class="input-border">
                                                                <input type="text" class="form-control" id="friend${$j}" name="friend[]" aria-describedby="emailHelp" placeholder="Team Member ID" value="">
                                                            </div>
                                                        </div>`;
                                        }
                                    }else{
                                        for($h=1; $h<= 5; $h++){
                                            enter1 += `<div class="form-group">
                                                            <label for="friend"${$h}>Add Friends</label>
                                                            <div class="input-border">
                                                                <input type="text" class="form-control" id="friend${$h}" name="friend[]" aria-describedby="emailHelp" placeholder="Team Member ID" value="">
                                                            </div>
                                                        </div>`;
                                        }
                                    }
                                }

                                enter1 += `<button type="submit" class="btn btn-gradient enterModal-submit">Submit</button>`;
                                $('.js-enter-form').append(enter1);

                                if(own_team_info){
                                    $('#loginModalLabel').html('your team!');
                                    $('[name=team_id]').val(own_team_info.id);
                                    $('.img-choosed').empty();
                                    if(!own_team_info.icon_id){
                                        own_team_info.icon_id = 1;
                                    }
                                    $('.img-choosed').html('<img src="/images/avatar/avatar-'+own_team_info.icon_id+'.svg" alt="title">');
                                    let login_uuid = '{!! Session::get('uuid') !!}';
                                    let long = max_team_size - (own_team_info.members.length - 1);

                                    if(leader_uuid == login_uuid && long <= 0){
                                        $('.enterModal-submit').attr("style", 'display:none');
                                    }

                                    $('input[name=name]').attr("disabled", true);
                                }else{
                                    $('#loginModalLabel').html('Create your team!');
                                    $('.choose-avatar-button').html('Choose Avatar');
                                }

                                $('#enterModal').modal('show');
                            })
                        }else{
                            $('#loginModal').modal('show');
                        }
                    });

                    // bracket
                    $('.open-bracketModal').on('click', function(){
                        $('#calendarModal').modal('hide');

                        $.ajax({
                            type: 'get',
                            url: eventConfigUrl,
                            data: {
                                'event_id' : event_id,
                            },
                            success: function(e){
                                e = JSON.parse(e);
                                console.log(e);

                                // $('.game-select').empty();
                                $('.event-select').empty();
                                $('.slider-bracket').empty();
                                let new_count = 0;

                                $.each(e.brackets, function(key, value){
                                    // var option1 = 
                                        // `<option value="${key+1}">Bracket ${key+1}</option>`;

                                    var option2 = 
                                        `<option value="${value.instance_index+1}">Events ${value.instance_index+1}</option>`;
                                        
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
                                                new_count = new_count + 1;
                                                option3 += 
                                                `<div class="item">
                                                    <div class="round">

                                                        <input type="hidden" name="rounds_count" value="${value.rounds.length}">
                                                        <input type="hidden" name="bracket_now" value="1">
                                                        <input type="hidden" name="events_now" value="${value.instance_index+1}">
                                                        <input type="hidden" name="round_now" value="${value2.round_index+1}">

                                                        <div class="round-header bracket-1-events-${value.instance_index+1}-round-${value2.round_index+1}" style="display: none;">Round ${value2.round_index+1}</div>
                                                        <div class="round-body bracket-1-events-${value.instance_index+1}-round-${value2.round_index+1}" style="display: none;">
                                                            <div class="round-status" style="display:none"><strong>In Progress: </strong><span class="timecount${key2}">01:38:05</span></div>
                                                            <div class="round-list bracket-line">`;

                                                                $.each(value2.branchs, function(key3, value3){
                                                                    option3 += `<div class="item">
                                                                        <div class="round-box">
                                                                            <div class="round-box-inner">
                                                                                <ul class="round-team">`;

                                                                                    $.each(value3, function(key4, value4){

                                                                                        getTeamName(value4.team_id);
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
                                                                                    })

                                                                                option3 += `</ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>`;
                                                                })

                                                            option3 += `</div>
                                                        </div>
                                                    </div>
                                                </div>`;
                                            })

                                        option3 += `</div>`;
                                    
                                    // $('.game-select').append(option1);
                                    $('.event-select').append(option2);
                                    $('.slider-bracket').append(option3);
                                    $('.bracket-1-events-1-round-' + new_count).show();
                                    $('[name=round_now]').val(new_count);

                                    $.each(e.rounds_end_time, function(key4, value4){
                                        initializeClock(key4, value4);
                                    })
                                    
                                })

                                $('#bracketModal').modal('show');
                            },
                        });
                    });
                },
                eventAfterAllRender: function(event) {
                    var now_month = event.title.split(' ').shift();
                    var now_month_number = month_to_number[now_month];
                    var right_number = now_month_number + 1;
                    var left_number = now_month_number - 1;

                    if(right_number > 12){
                        right_number = 1;
                    }
                    var right = month_to_en[right_number];

                    if(left_number < 1){
                        left_number = 12;
                    }
                    var left = month_to_en[left_number];

                    $('.fc-next-button').html('<span class="pn pn-next">'+right+'</span><span class="fa fa-chevron-right"></span>');
                    $('.fc-prev-button').html('<span class="fa fa-chevron-left"></span><span class="pn pn-prev">'+left+'</span>');
                }
            })
        });
        
        // enter-start
        async function viewEventConfigData (event_id) {
            var uuid = '{!! Session::get('uuid') !!}';
            var token = '{!! Session::get('token') !!}';
            let a =  await axios.get(`{!! env('BRACKET_API_URL') !!}events/${event_id}`,{
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            try {
                var b = await axios.get(`{!! env('BRACKET_API_URL') !!}events/${event_id}/team`,{
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    params: {
                        'uuid' : uuid,
                    },
                })
            } catch (e) {
                b = 500;
            }
            return {
                a,b
            }
        }

        $('.choose-avatar').on('click', function(){
            $('#enterModal').modal('hide');
            $('#chooseAvatarModal').modal('show');
        });

        $('.choose-avatar-cancel').on('click', function(){
            $('#chooseAvatarModal').modal('hide');
            $('#enterModal').modal('show');
        });

        $('.choose-avatar-submit').on('click', function(){
            var checked = $('input[name=radio]:checked').val();
            $('[name=avatar_id]').val(checked);
            $('#chooseAvatarModal').modal('hide');
            $('#enterModal').modal('show');
            $('.img-choosed').empty();
            $('.img-choosed').html('<img src="/images/avatar/avatar-'+checked+'.svg" alt="title">');
        });

        function getImg(event_id){
            var get_img_url = base_path + '/tournament/' + event_id;
            
            $.ajax({
                type: 'get',
                url: get_img_url,
                async: false,
                success: function(e){
                    getImg_data = JSON.parse(e);
                },
            });
        }
        // enter-end

        // bracket-start
        function getTimeRemaining(endtime){
            const total = (endtime*1000) - Date.parse(new Date()); 
            const seconds = Math.floor( (endtime/1000) % 60 ); 
            const minutes = Math.floor( (endtime/1000/60) % 60 ); 
            const hours = Math.floor( (endtime/(1000*60*60)) % 24 ); 
            const days = Math.floor( endtime/(1000*60*60*24) ); 
            console.log(total);
            return {
                total, 
                days, 
                hours, 
                minutes, 
                seconds 
            };
        }

        function initializeClock(key, endtime) {
            const clock = $('.timecount'+key);
            const timeinterval = setInterval(() => {
                console.log(123);
                const t = getTimeRemaining(endtime);
                clock.html(t.days + 'D ' + t.hours + ":" + t.minutes + ":" + t.seconds);
                if (t.total <= 0) {
                    clearInterval(timeinterval);
                }
            },1000);
        }

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
        // bracket-end
    });
</script>
@stop

@section('Content')
@if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
<div class="page-banner" style="background-image: url(https://www.atgames.net/leaderboards/images/page-banner-schedule.jpg);">
@else
<div class="page-banner" style="background-image: url(/images/page-banner-schedule.jpg);">
@endif
    <div class="title"><span>Calendar</span> of Events</div>
</div>
<div class="page">
    <div class="container">
        <div class="page-inner">
            <div class="filter filterSwitch">
                <div class="filter-01">
                </div>
                <div class="filter-02">
                    <div class="navLink">
                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            <a href="https://www.atgames.net/leaderboards/schedule" class="btn btn-link">
                        @else
                            <a href="{!! action('Frontend\Schedule@pageSchedule') !!}" class="btn btn-link">
                        @endif
                            <span>View List</span>
                            <i class="icon-an-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div id="calendar"></div>

            <div class="calendar-info">
                <div class="calendar-info-01">
                    <strong>Today’s Date: </strong>{{ @$time_now }}
                </div>
                <div class="calendar-info-02">
                    <div class="status status-active">
                        <i class="icon-an-active" aria-hidden="true"></i>
                        Active
                    </div>
                    <div class="status status-upcoming">
                        <i class="icon-an-upcoming" aria-hidden="true"></i>
                        Upcoming
                    </div>
                    <div class="status status-expired">
                        <i class="icon-an-expired" aria-hidden="true"></i>
                        Expired
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Calendar-->
<div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="calendarModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <div>close</div>
        </button>
        <div class="modal-content modal-calendar">
            <div class="modal-body">
                <div class="eventHeader">
                    <div class="eventHeader-img">
                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            <img class="icon-src" src="https://www.atgames.net/leaderboards/images/event-highScore.svg">
                        @else
                            <img class="icon-src" src="{!! asset('/images/event-highScore.svg') !!}">
                        @endif
                        
                    </div>
                    <div class="eventHeader-title">Tournament</div>
                    <div class="eventHeader-titleSub">
                        
                    </div>
                    <div class="eventHeader-date"></div>
                </div>
                <div class="sliderGame">
                    <div id="sliderGame" class="img-slider">
                        
                    </div>
                </div>
                <div class="eventInfo">
                    <div class="eventInfo-title">

                    </div>
                    <p class="eventInfo-title-description">
                        
                    </p>
                    <div class="eventInfo-btn">
                        {{-- <button type="button" class="btn btn-border" data-toggle="modal" data-target="#alertsModal">
                            <div>Get Alerts</div>
                        </button>
                        <div class="status event-status">
                            
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Enter -->
<div class="modal fade" id="enterModal" tabindex="-1" role="dialog" aria-labelledby="enterModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <div>close</div>
        </button>
        <div class="modal-content">
            <div class="enter">
                <div class="enter-header">
                    <h5 class="modal-title" id="loginModalLabel"></h5>
                    {{-- <p><strong>your team then view your bracket.</strong> Quis lectus ipsum eu, lectus ac imperdiet
                        egestas.
                        Tellus arcu vulputate sollicitudin vestibulum dignissim nulla gravida eget nam enim vitae sit
                        purus
                        dui ac sed. </p> --}}
                </div>
                <div class="enter-row">
                    <div class="enter-avatar">
                        <div class="avatar">
                            <div class="img-by img-by-1by1 img-choosed">
                                @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                                    <img src="https://www.atgames.net/leaderboards/images/avatar/avatar-1.svg" alt="title">
                                @else
                                    <img src="{!! asset('/images/avatar/avatar-1.svg') !!}" alt="title">
                                @endif
                            </div>
                        </div>
                        <a href="#" class="link-underline choose-avatar choose-avatar-button">
                            
                        </a>
                    </div>
                    <div class="enter-form">
                        {!! Form::open(array('url' => route('createTeam'), 'method' => 'post', 'class' => 'form-sp js-enter-form')) !!}
                            <input type="hidden" name="team_id" value="">
                            <input type="hidden" name="avatar_id" value="">
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Choose Avatar -->
<div class="modal fade" id="chooseAvatarModal" tabindex="-1" role="dialog" aria-labelledby="chooseAvatarModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <div>close</div>
        </button>
        <div class="modal-content" style="height: 80vh; overflow-y: auto;">
            <div class="chooseAvatar">
                <div class="chooseAvatar-header">
                    <h5 class="modal-title" id="loginModalLabel">Choose Avatar</h5>
                </div>
                <div class="chooseAvatar-list">
                    @for($i=1; $i<=26; $i++)
                    <div class="item">
                        <input id="radio{{$i}}" class="radio-button" type="radio" name="radio" value="{{$i}}" />
                        <label for="radio{{$i}}" class="radio-tile-label">
                            <div class="img-by img-by-1by1">
                                @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                                    <img src="{!! 'https://www.atgames.net/leaderboards/images/avatar/avatar-' . $i . '.svg' !!}" alt="title">
                                @else
                                    <img src="{!! asset('/images/avatar/avatar-' . $i . '.svg') !!}" alt="title">
                                @endif
                            </div>
                        </label>
                    </div>
                    @endfor
                </div>
                <div class="chooseAvatar-footer">
                    <button type="button" class="btn btn-border choose-avatar-cancel">
                        <div>Cancel</div>
                    </button>
                    <button type="submit" class="btn btn-gradient choose-avatar-submit">Choose</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bracket -->
<div class="modal fade" id="bracketModal" tabindex="-1" role="dialog" aria-labelledby="bracketModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <div>close</div>
        </button>
        <div class="modal-content">
            <div class="bracket all-bracket-model">
                <div class="bracket-header">
                    <form class="form-sp">
                        <div class="form-group">
                            <label for="game">Game</label>
                            <div class="input-border select selectSp">
                                <select name="game" class="form-control game-select">
                                    <option selected>Bracket A</option>
                                    <!-- <option>Bracket B</option>
                                    <option>Bracket C</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="event">Events</label>
                            <div class="input-border select">
                                <select name="event" class="form-control event-select">
                                    <!-- <option selected>Events</option>
                                    <option>Events</option>
                                    <option>Events</option> -->
                                </select>
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
                    <div id="sliderBracket round-line">
                        <div class="item">
                            <div class="round">
                                <div class="round-header">Round 1</div>
                                <div class="round-body">
                                    <!-- <div class="round-status"><strong>In Progress: </strong>01:38:05</div> -->
                                    <div class="round-list bracket-line">
                                        <div class="item">
                                            <div class="round-box">
                                                <div class="round-box-inner">
                                                    <ul class="round-team">
                                                        <li>
                                                            1<strong>YOUR TEAM</strong>
                                                        </li>
                                                        <li>
                                                            2<strong>YOUR TEAM</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="round-box">
                                                <div class="round-box-inner">
                                                    <ul class="round-team">
                                                        <li>
                                                            3<strong>YOUR TEAM</strong>
                                                        </li>
                                                        <li>
                                                            4<strong>YOUR TEAM</strong>
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
                                    <!-- <div class="round-status"><strong>In Progress: </strong>01:38:05</div> -->
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
                                    <!-- <div class="round-status"><strong>In Progress: </strong>01:38:05</div> -->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<!-- <script src="/js/calendar.js"></script> -->
@stop