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

        function getTimeRemaining(endtime){
            // const total = (endtime*1000) + (3600*1000*8) - Date.parse(new Date());
            const total = (endtime*1000) - Date.parse(new Date());
            const seconds = Math.floor( (total/1000) % 60 ); 
            const minutes = Math.floor( (total/1000/60) % 60 ); 
            const hours = Math.floor( (total/(1000*60*60)) % 24 ); 
            const days = Math.floor( total/(1000*60*60*24) ); 
            return {
                total, 
                days, 
                hours, 
                minutes, 
                seconds 
            };
        }

        function initializeClock(key, endtime, count_rounds) {
            const clock = $('.timecount'+key);
            const timeinterval = setInterval(() => {
                const t = getTimeRemaining(endtime);
                console.log(t.seconds)
                if(Math.sign(t.seconds)){
                    if(t.seconds < 0){
                        
                    }else{
                        $('.count-text-' + count_rounds).text('In Progress : ');
                        document.getElementById('timecount'+key).innerHTML = t.days + 'D ' + t.hours + ":" + t.minutes + ":" + t.seconds;
                    }
                }
                
                if (t.total <= 0) {
                    clearInterval(timeinterval);
                }
            },1000);
        }

        $(document).on('click', '#coll', function(){
            setTimeout(function() {
                $('button[aria-expanded="true"]').find('#coll').html('collapse').closest('.card').addClass('card-active')
                $('button[aria-expanded="false"]').find('#coll').html('view').closest('.card').removeClass('card-active')
            }, 1);

            var event_id = $(this).parents('.card').find('input[name=event_id]').val();
            var style = $(this).parents('.card').find('input[name=style]').val();
            
            if(style == 'bracket'){
                let p_data = viewEventConfigData(event_id);

                p_data.finally( val => {
                    
                })

                viewEventConfigData(event_id).then ( res => {
                    
                    var team_info = res.b;

                    if(team_info != 500){
                        $(this).parents('.card').find('.open-enterModal').text('View Team');
                    }

                    getImg(event_id);

                    $('.sliderGame-id-' + event_id).empty();
                    $('.tns-nav').remove();

                    $.each(getImg_data.games, function(key, value){
                        var set_img = `
                            <div class="item">
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
                            </div>
                        `;

                        $('.sliderGame-id-' + event_id).append(set_img);
                    })

                    var slider = tns({
                        container: '#sliderGame' + event_id,
                        controls: false,
                        nav: true,
                        navPosition: "bottom",
                        mouseDrag: true,
                        autoplay: true,
                        autoplayButtonOutput: false,
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
                })
            }else{
                getImg(event_id);

                $('.sliderGame-id-' + event_id).empty();
                $('.tns-nav').remove();

                $.each(getImg_data.games, function(key, value){
                    var set_img = `
                        <div class="item">
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
                        </div>
                    `;

                    $('.sliderGame-id-' + event_id).append(set_img);
                })

                var slider = tns({
                    container: '#sliderGame' + event_id,
                    controls: false,
                    nav: true,
                    navPosition: "bottom",
                    mouseDrag: true,
                    autoplay: true,
                    autoplayButtonOutput: false,
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
            }
        });

        function getImg(event_id){
            
            var host_name = window.location.hostname;
            if(host_name == 'www.atgames.net' || host_name == 'acnet-lb.atgames.net'){
                var get_img_url = 'https://www.atgames.net/leaderboards/tournament/' + event_id;
            }else{
                var get_img_url = base_path + '/tournament/' + event_id;
            }
            
            $.ajax({
                type: 'get',
                url: get_img_url,
                async: false,
                success: function(e){
                    getImg_data = JSON.parse(e);
                    console.log(getImg_data);
                },
            });
        }

        var newest_event_id = '{!! $newest_event_id !!}'
        if(newest_event_id != 0){
            var slider = tns({
                container: '#sliderGame' + newest_event_id,
                controls: false,
                nav: true,
                navPosition: "bottom",
                mouseDrag: true,
                autoplay: true,
                autoplayButtonOutput: false,
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

        $('.open-bracketModal').on('click', function(){
            var name = $(this).parents('.card').find('[name=name]').val();
            var event_id = $(this).parents('.card').find('[name=event_id]').val();
            var url = $(this).parents('.card').find('[name=url]').val();
            console.log(event_id);
            $.ajax({
                type: 'get',
                url: url,
                data: {
                    'event_id' : event_id,
                },
                success: function(e){
                    e = JSON.parse(e);
                    console.log(e);

                    $('.selectSp2').empty();
                    $('.event-select').empty();
                    $('.slider-bracket').empty();
                    let new_count = 0;
                    var status = 'Progress';

                    $.each(e.brackets, function(okey, ovalue){
                        var count_rounds = ovalue.rounds.length;
                        var number_of_brackets = e.number_of_brackets;
                        
                        if(Math.pow(2, count_rounds-1) == number_of_brackets){
                            var status = 'End';
                            console.log(status);

                            var end_data = ovalue.rounds.slice(-2);
                            var end_data2 = end_data.shift();

                            var team_id_1 = 0;
                            var score_1 = 0;
                            var team_id_2 = 0;
                            var score_2 = 0;

                            $.each(end_data2.branchs, function(akey, avalue){
                                $.each(avalue, function(akey2, avalue2){

                                    if(akey2 == 0){
                                        team_id_1 = avalue2.team_id;
                                        score_1 = avalue2.current_score;
                                    }else{
                                        team_id_2 = avalue2.team_id;
                                        score_2 = avalue2.current_score;
                                    }
                                })
                            })

                            if((score_1 > score_2)||(score_1 == score_2)){
                                getTeamName(team_id_1);
                                var champion_id = team_id_1;
                                var champion_name = teamName;

                                getTeamName(team_id_2);
                                var second_id = team_id_2;
                                var second_name = teamName;
                            }else{
                                getTeamName(team_id_2);
                                var champion_id = team_id_2;
                                var champion_name = teamName;

                                getTeamName(team_id_1);
                                var second_id = team_id_1;
                                var second_name = teamName;
                            }
                            $('.title-name').text(name);
                            $('.winner-name').text(champion_name);
                            $('.for-champion_name').text(champion_name);
                            $('.for-second_name').text(second_name);
                            $('[name=results_name]').val(name);
                            $('[name=results_event_id]').val(event_id);

                            $('#resultsModal').modal('show');
                        }else{
                            console.log(status);
                            $.each(e.brackets, function(key, value){
                                var option1 = 
                                    `${name}`;

                                var option2 = 
                                    `<option value="${value.instance_index+1}">Bracket ${value.instance_index+1}</option>`;
                                    
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
                                            var branchs_length = value2.branchs.length;
                                            option3 += 
                                            `<div class="item">
                                                <div class="round">

                                                    <input type="hidden" name="rounds_count" value="${value.rounds.length}">
                                                    <input type="hidden" name="bracket_now" value="1">
                                                    <input type="hidden" name="events_now" value="${value.instance_index+1}">
                                                    <input type="hidden" name="round_now" value="${value2.round_index+1}">

                                                    <div class="round-${value2.round_index+1}-name round-header bracket-1-events-${value.instance_index+1}-round-${value2.round_index+1}" style="display: none;">Round ${value2.round_index+1}</div>
                                                    <div class="round-body bracket-1-events-${value.instance_index+1}-round-${value2.round_index+1}" style="display: none;">
                                                        <div class="round-status"><strong class="count-text count-text-${value2.round_index+1}">Round End</strong><span id="timecount${key2}" class="timecount${key2}"></span></div>`;

                                                            if(branchs_length == 1){
                                                                option3 += `<div class="round-list bracket-line">`;
                                                            }else{
                                                                if(new_count == count_rounds){
                                                                    option3 += `<div class="round-list bracket-line">`;
                                                                }else{
                                                                    option3 += `<div class="round-list line bracket-line">`;
                                                                }
                                                            }

                                                            
                                                            $.each(value2.branchs, function(key3, value3){
                                                                if((key3%2) == 0){
                                                                    option3 += `<div class="item">`;
                                                                }
                                                                
                                                                option3 += `<div class="round-box">
                                                                    <div class="round-box-inner">
                                                                        <ul class="round-team">`;

                                                                            var bracket_team_id_1 = 0;
                                                                            var bracket_score_1 = 0;
                                                                            var bracket_team_id_2 = 0;
                                                                            var bracket_score_2 = 0;

                                                                            if(new_count == count_rounds){
                                                                                $.each(value3, function(key411, value411){
                                                                                    getTeamName(value411.team_id);
                                                                                    if(value411.team_id == -1){
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
                                                                                                <span>${value411.branch_index+1}</span>
                                                                                                <strong>${teamName}</strong>
                                                                                            </div>
                                                                                            <div class="round-team-score">${value411.current_score}</div>
                                                                                        </li>`;
                                                                                    }
                                                                                })
                                                                            }else{
                                                                                $.each(value3, function(ookey4, oovalue4){
                                                                                    if(ookey4 == 0){
                                                                                        bracket_team_id_1 = oovalue4.team_id;
                                                                                        bracket_score_1 = oovalue4.current_score;
                                                                                    }else{
                                                                                        bracket_team_id_2 = oovalue4.team_id;
                                                                                        bracket_score_2 = oovalue4.current_score;
                                                                                    }
                                                                                })

                                                                                $.each(value3, function(key4, value4){
                                                                                    getTeamName(value4.team_id);
                                                                                    if((bracket_score_1 > bracket_score_2)||bracket_score_1 == bracket_score_2){
                                                                                        if(bracket_team_id_1 == value4.team_id){
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
                                                                                        }else{
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
                                                                                        }
                                                                                    }else if(bracket_score_2 > bracket_score_1){
                                                                                        if(bracket_team_id_2 == value4.team_id){
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
                                                                                        }else{
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
                                                                                        }
                                                                                    }
                                                                                })
                                                                            }
                                                                            
                                                                        option3 += `</ul>
                                                                    </div>
                                                                </div>`;

                                                                if((key3%2) != 0){
                                                                    option3 += `</div>`;
                                                                }
                                                            })

                                                        option3 += `</div>
                                                    </div>
                                                </div>
                                            </div>`;
                                        })

                                    option3 += `</div>`;
                                
                                $('.selectSp2').append(option1);
                                $('.event-select').append(option2);
                                $('.slider-bracket').append(option3);
                                $('.bracket-1-events-1-round-' + new_count).show();
                                $('[name=round_now]').val(new_count);

                                if(status == 'End'){
                                    let semi = (count_rounds-2);
                                    $('.round-' + semi + '-name').text('SEMI-FINAL Round');

                                    let finals = (count_rounds-1);
                                    $('.round-' + finals + '-name').text('FINAL Round');

                                    $('.round-' + count_rounds + '-name').text('FINAL WINNER');

                                    $('.count-text').text('Complete');
                                }else{
                                    if(count_rounds > 1){
                                        for(i=1; i<count_rounds; i++){
                                            $('.count-text-' + (count_rounds-i)).text('Round ' + (count_rounds-i) + ' Complete');
                                        }
                                    }
                                }

                                $.each(e.rounds_end_time, function(key4, value4){
                                    initializeClock(key4, value4, count_rounds);
                                })
                                
                            })

                            $('#bracketModal').modal('show');
                        }
                    })
                },
            });
        });
        
        $('.open-bracketModal2').on('click', function(){
            var name = $(this).find('[name=results_name]').val();
            var event_id = $(this).find('[name=results_event_id]').val();
            var url = $(this).find('[name=results_url]').val();
            console.log(event_id);
            $.ajax({
                type: 'get',
                url: url,
                data: {
                    'event_id' : event_id,
                },
                success: function(e){
                    e = JSON.parse(e);
                    console.log(e);

                    $('.selectSp2').empty();
                    $('.event-select').empty();
                    $('.slider-bracket').empty();
                    let new_count = 0;
                    var status = 'Progress';

                    $.each(e.brackets, function(okey, ovalue){
                        var count_rounds = ovalue.rounds.length;
                        var number_of_brackets = e.number_of_brackets;

                        var count_rounds = ovalue.rounds.length;
                        var number_of_brackets = e.number_of_brackets;
                        
                        if(Math.pow(2, count_rounds-1) == number_of_brackets){
                            var status = 'End';
                        }
                        
                        $.each(e.brackets, function(key, value){
                            var option1 = 
                                `${name}`;

                            var option2 = 
                                `<option value="${value.instance_index+1}">Bracket ${value.instance_index+1}</option>`;
                                
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
                                        var branchs_length = value2.branchs.length;
                                        option3 += 
                                        `<div class="item">
                                            <div class="round">

                                                <input type="hidden" name="rounds_count" value="${value.rounds.length}">
                                                <input type="hidden" name="bracket_now" value="1">
                                                <input type="hidden" name="events_now" value="${value.instance_index+1}">
                                                <input type="hidden" name="round_now" value="${value2.round_index+1}">

                                                <div class="round-${value2.round_index+1}-name round-header bracket-1-events-${value.instance_index+1}-round-${value2.round_index+1}" style="display: none;">Round ${value2.round_index+1}</div>
                                                <div class="round-body bracket-1-events-${value.instance_index+1}-round-${value2.round_index+1}" style="display: none;">
                                                    <div class="round-status"><strong class="count-text">In Progress :  </strong><span id="timecount${key2}" class="timecount${key2}"></span></div>`;

                                                        if(branchs_length == 1){
                                                            option3 += `<div class="round-list bracket-line">`;
                                                        }else{
                                                            option3 += `<div class="round-list line bracket-line">`;
                                                        }

                                                        $.each(value2.branchs, function(key3, value3){
                                                            if((key3%2) == 0){
                                                                option3 += `<div class="item">`;
                                                            }
                                                            
                                                            option3 += `<div class="round-box">
                                                                <div class="round-box-inner">
                                                                    <ul class="round-team">`;

                                                                        var bracket_team_id_1 = 0;
                                                                        var bracket_score_1 = 0;
                                                                        var bracket_team_id_2 = 0;
                                                                        var bracket_score_2 = 0;

                                                                        $.each(value3, function(ookey4, oovalue4){
                                                                            if(ookey4 == 0){
                                                                                bracket_team_id_1 = oovalue4.team_id;
                                                                                bracket_score_1 = oovalue4.current_score;
                                                                            }else{
                                                                                bracket_team_id_2 = oovalue4.team_id;
                                                                                bracket_score_2 = oovalue4.current_score;
                                                                            }
                                                                        })

                                                                        $.each(value3, function(key4, value4){
                                                                            getTeamName(value4.team_id);
                                                                            if((bracket_score_1 > bracket_score_2)||bracket_score_1 == bracket_score_2){
                                                                                if(bracket_team_id_1 == value4.team_id){
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
                                                                                }else{
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
                                                                                }
                                                                            }else if(bracket_score_2 > bracket_score_1){
                                                                                if(bracket_team_id_2 == value4.team_id){
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
                                                                                }else{
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
                                                                                }
                                                                            }
                                                                        })
                                                                        
                                                                    option3 += `</ul>
                                                                </div>
                                                            </div>`;

                                                            if((key3%2) != 0){
                                                                option3 += `</div>`;
                                                            }
                                                        })

                                                    option3 += `</div>
                                                </div>
                                            </div>
                                        </div>`;
                                    })

                                option3 += `</div>`;
                            
                            $('.selectSp2').append(option1);
                            $('.event-select').append(option2);
                            $('.slider-bracket').append(option3);
                            $('.bracket-1-events-1-round-' + new_count).show();
                            $('[name=round_now]').val(new_count);

                            let semi = (count_rounds-2);
                            $('.round-' + semi + '-name').text('SEMI-FINAL Round');

                            let finals = (count_rounds-1);
                            $('.round-' + finals + '-name').text('FINAL Round');

                            $('.round-' + count_rounds + '-name').text('FINAL WINNER');

                            if(status == 'End'){
                                $('.count-text').text('Complete');
                            }
                            
                        })
                        $('#resultsModal').modal('hide');
                        $('#bracketModal').modal('show');
                    })
                },
            });
        });

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

        // var slider = tns({
        //     container: '#sliderGameFinal',
        //     controls: false,
        //     nav: true,
        //     navPosition: "bottom",
        //     mouseDrag: true,
        //     autoplay: true,
        //     autoplayButtonOutput: false,
        //     rewind: false,
        //     gutter: 16,
        //     responsive: {
        //         0: {
        //             items: 1.3
        //         },
        //         768: {
        //             items: 2
        //         },
        //         992: {
        //             items: 3
        //         },
        //         1200: {
        //             items: 4
        //         }
        //     }
        // });

        $('.open-enterModal').on('click', function(){

            var socialUserName = '{!! Session::get('socialUserName') !!}';
            var had_login = '{!! $had_login !!}';
            if(had_login){
                
                var event_id = $(this).parents('.card').find('[name=event_id]').val();

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

                        if(max_team_size != -1){
                            let long = max_team_size - (own_team_info.members.length - 1);

                            if(leader_uuid != login_uuid || long <= 0){
                                $('.enterModal-submit').attr("style", 'display:none');
                            }
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

        $('.get-alerts').on('click', function(){

            var had_login = '{!! $had_login !!}';
            if(had_login){
                
            }else{
                $('#loginModal').modal('show');
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
            <div class="filter filterSwitch">
                <div class="filter-01">
                    <div class="navLink">
                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            <a href="https://www.atgames.net/leaderboards/schedule" class="btn btn-link @if($rule == '') active @endif">
                                <i class="icon-an-all" aria-hidden="true"></i>
                                <span>All</span>
                            </a>
                            <a href="https://www.atgames.net/leaderboards/schedule?rule=active" class="btn btn-link @if($rule == 'active') active @endif">
                                <i class="icon-an-active" aria-hidden="true"></i>
                                <span>Active</span>
                            </a>
                            <a href="https://www.atgames.net/leaderboards/schedule?rule=upcoming" class="btn btn-link @if($rule == 'upcoming') active @endif">
                                <i class="icon-an-upcoming" aria-hidden="true"></i>
                                <span>Upcoming</span>
                            </a>
                            <a href="https://www.atgames.net/leaderboards/schedule?rule=expired" class="btn btn-link @if($rule == 'expired') active @endif">
                                <i class="icon-an-expired" aria-hidden="true"></i>
                                <span>Expired</span>
                            </a>
                        @else
                            <a href="{!! action('Frontend\Schedule@pageSchedule') !!}" class="btn btn-link @if($rule == '') active @endif">
                                <i class="icon-an-all" aria-hidden="true"></i>
                                <span>All</span>
                            </a>
                            <a href="{!! action('Frontend\Schedule@pageSchedule', array('rule' => 'active')) !!}" class="btn btn-link @if($rule == 'active') active @endif">
                                <i class="icon-an-active" aria-hidden="true"></i>
                                <span>Active</span>
                            </a>
                            <a href="{!! action('Frontend\Schedule@pageSchedule', array('rule' => 'upcoming')) !!}" class="btn btn-link @if($rule == 'upcoming') active @endif">
                                <i class="icon-an-upcoming" aria-hidden="true"></i>
                                <span>Upcoming</span>
                            </a>
                            <a href="{!! action('Frontend\Schedule@pageSchedule', array('rule' => 'expired')) !!}" class="btn btn-link @if($rule == 'expired') active @endif">
                                <i class="icon-an-expired" aria-hidden="true"></i>
                                <span>Expired</span>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="filter-02">
                    <div class="navLink">
                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                            <a href="https://www.atgames.net/leaderboards/schedule/calendar" class="btn btn-link">
                        @else
                            <a href="{!! action('Frontend\Schedule@pageScheduleCalendar') !!}" class="btn btn-link">
                        @endif
                                <span>View Calendar</span>
                                <i class="icon-an-calendar" aria-hidden="true"></i>
                            </a>
                    </div>
                </div>
            </div>
            @if(@$tournaments_data['tournaments'])
                <div class="accordion accordion-event" id="accordionEvent">
                @foreach(@$tournaments_data['tournaments'] as $key => $value)
                <div class="card @if($key == 0) card-active @endif">
                    <div class="card-header" id="heading{{ $value['id'] }}">
                        <button class="btn btn-border" type="button" data-toggle="collapse" data-target="#collapse-{{ $value['id'] }}"
                            aria-expanded="@if($key == 0) true @endif" aria-controls="collapse-{{ $value['id'] }}">
                            <div id="coll">
                                @if($key == 0)
                                    collapse
                                @else
                                    view
                                @endif
                            </div>
                        </button>
                        <div class="eventHeader">
                            <div class="eventHeader-img">
                                @if($value['style'] == 'contest')
                                    <img src="{!! asset('/images/event-highScore-w.svg') !!}">
                                @elseif($value['style'] == 'bracket')
                                    <img src="{!! asset('/images/event-tournament-w.svg') !!}">
                                @endif
                            </div>
                            <div class="eventHeader-titleSub">
                                {{ $value['name'] }}
                            </div>
                            <div class="eventHeader-date"><strong>Event date: </strong>{{ $value['start'] }} through {{ $value['end'] }}
                            </div>
                        </div>
                    </div>
                    {!! Form::hidden('name', $value['name']) !!}
                    {!! Form::hidden('event_id', $value['id']) !!}
                    {!! Form::hidden('style', $value['style']) !!}
                    {!! Form::hidden('url', @action('CurlUSAAPI@viewEventAllData')) !!}
                    <div id="collapse-{{ $value['id'] }}" class="collapse @if($key == 0) show @endif" aria-labelledby="heading{{ $value['id'] }}" data-parent="#accordionEvent">
                        <div class="card-body">
                            <div class="sliderGame">
                                <div id="sliderGame{{ $value['id'] }}" class="sliderGame-id-{{ $value['id'] }}">
                                    @if($key == 0)
                                        @foreach(@$value['info']['games'] as $key2 => $value2)
                                        <div class="item">
                                            <div class="game">
                                                <div class="game-border">
                                                    <div class="game-inner">
                                                        <div class="title"><span>{{ $key2+1 }}</span>{{ $value2['name'] }}</div>
                                                        <div class="cover">
                                                            <div class="img-by img-by-5by2">
                                                                <img src="{{ $value2['boxart'] }}" alt="title">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="eventInfo">
                                <div class="eventInfo-title">
                                    {{ $value['name'] }}
                                </div>
                                <div class="eventInfo-titleSub">
                                    {!! nl2br(e($value['description'])) !!}
                                </div>
                                <p>{{ $value['start'] }} - {{ $value['end'] }} 
                                    @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                                    <a href="https://www.atgames.net/leaderboards/schedule/calendar" class="btn btn-link">
                                    @else
                                    <a href="{!! action('Frontend\Schedule@pageScheduleCalendar') !!}" class="link-underline">
                                    @endif
                                    View Calendar</a>
                                </p>
                                <div class="eventInfo-btn">
                                    @if(strtolower($value['status']) == 'upcoming')
                                        @if($key == 0)
                                            @if(@$value['team_info'] == 1)
                                                <button type="button" class="btn btn-gradient open-enterModal">View Team</button>
                                            @else
                                                @if($value['style'] == 'bracket')
                                                    <button type="button" class="btn btn-gradient open-enterModal">Enter Tournament</button>
                                                @endif
                                            @endif
                                        @else
                                            @if($value['style'] == 'bracket')
                                                <button type="button" class="btn btn-gradient open-enterModal">Enter Tournament</button>
                                            @endif
                                        @endif
                                    @endif
                                    @if(strtolower($value['status']) == 'active')
                                        @if($value['style'] == 'contest')
                                            @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                                                <a href="https://www.atgames.net/leaderboards/highscore/result/{{ $value['id'] }}">
                                            @else
                                                <a href="{!! action('Frontend\Highscore@pageHighScoreResult', array('event_id' => $value['id'])) !!}">
                                            @endif
                                                <button type="button" class="btn btn-gradient">View HighScore</button>
                                            </a>
                                        @elseif($value['style'] == 'bracket')
                                            <button type="button" class="btn btn-gradient open-bracketModal">View bracket</button>
                                        @endif
                                    @endif
                                    @if(strtolower($value['status']) == 'expired')
                                        @if(Request::root() == 'https://www.atgames.net' || Request::root() == 'http://acnet-lb.atgames.net' || Request::root() == 'https://acnet-lb.atgames.net')
                                            @if($value['style'] == 'contest')
                                                <a href="https://www.atgames.net/leaderboards/highscore/result/{{ $value['id'] }}">
                                                    <button type="button" class="btn btn-gradient">HighScore Results</button>
                                                </a>
                                            @elseif($value['style'] == 'bracket')
                                                <a href="https://www.atgames.net/leaderboards/tournament/result/{{ $value['id'] }}">
                                                    <button type="button" class="btn btn-gradient">Tournament Results</button>
                                                </a>
                                            @endif
                                        @else
                                            @if($value['style'] == 'contest')
                                                <a href="{!! action('Frontend\Highscore@pageHighScoreResult', array('event_id' => $value['id'])) !!}">
                                                    <button type="button" class="btn btn-gradient">HighScore Results</button>
                                                </a>
                                            @elseif($value['style'] == 'bracket')
                                                <a href="{!! action('Frontend\Tournament@pageTournamentResult', array('event_id' => $value['id'])) !!}">
                                                    <button type="button" class="btn btn-gradient">Tournament Results</button>
                                                </a>
                                            @endif
                                        @endif
                                    @endif
                                    @if(strtolower($value['status']) != 'expired')
                                        <!-- <button type="button" class="btn btn-border get-alerts">
                                            <div>Get Alerts</div>
                                        </button> -->
                                    @endif
                                    <div class="status status-{{ strtolower($value['status']) }}">
                                        <i class="icon-an-{{ strtolower($value['status']) }}" aria-hidden="true"></i>{{ $value['status'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
                <div class="nocontent">There are no events.</div>
            @endif
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
                                <img src="{!! asset('/images/avatar/avatar-1.svg') !!}" alt="title">
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
                                <img src="{!! asset('/images/avatar/avatar-' . $i . '.svg') !!}" alt="title">
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
                        <div class="form-group ">
                            <div class="eventInfo-title eventHeader-titleSub selectSp2">
                                
                            </div>

                            {{-- <label for="game">Game</label>
                            <div class="input-border select selectSp">
                                <select name="game" class="form-control game-select">
                                    <option selected>Bracket A</option>
                                    <!-- <option>Bracket B</option>
                                    <option>Bracket C</option> -->
                                </select>
                            </div> --}}
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

<!-- Modal Results -->
<div class="modal fade" id="resultsModal" tabindex="-1" role="dialog" aria-labelledby="resultsModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <div>close</div>
        </button>
        <div class="modal-content">
            <div class="modal-btn">
                <button class="btn btn-border open-bracketModal2" type="button" id="search">
                    {!! Form::hidden('results_name', '') !!}
                    {!! Form::hidden('results_event_id', '') !!}
                    {!! Form::hidden('results_url', @action('CurlUSAAPI@viewEventAllData')) !!}
                    <div>
                        <i class="icon-an-bracket" aria-hidden="true"></i>
                        Bracket
                    </div>
                </button>
            </div>
            <div class="bannerTournament"><span>Tournament </span> Winners!</div>
            <div class="bracket">
                <div class="bracket-header">
                    <form class="form-sp">
                        <div class="eventInfo-title eventHeader-titleSub selectSp2 title-name">
                                
                        </div>
                        <!-- <div class="form-group">
                            <label for="game">Game</label>
                            <div class="input-border select selectSp">
                                <select id="game" class="form-control">
                                    <option selected>Bracket A</option>
                                    <option>Bracket B</option>
                                    <option>Bracket C</option>
                                </select>
                            </div>
                        </div> -->
                    </form>
                </div>
                <div class="tournamentWinner">
                    <div class="avatar">
                        <div class="img-by img-by-1by1">
                            <img src="/images/avatar-01.svg" alt="title">
                        </div>
                    </div>
                    <div class="tournamentWinner-title winner-name">Your Team</div>
                    <div class="tournamentWinner-list">
                        <div class="item winner">
                            <div class="avatar">
                                <div class="img-by img-by-1by1">
                                    <img src="{!! asset('/images/avatar-01.svg') !!}" alt="title">
                                </div>
                            </div>
                            <div class="item-title">
                                <div class="item-title-inner">
                                    1<strong class="for-champion_name">YOUR TEAM</strong>
                                </div>
                            </div>
                        </div>
                        <div class="item loser">
                            <div class="avatar">
                                <div class="img-by img-by-1by1">
                                    <img src="{!! asset('/images/avatar-01.svg') !!}" alt="title">
                                </div>
                            </div>
                            <div class="item-title">
                                <div class="item-title-inner">
                                    2<strong class="for-second_name">YOUR TEAM</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="listTournament">
                <div id="sliderGameFinal">
                    <div class="item">
                        <div class="game">
                            <div class="game-border">
                                <div class="game-inner">
                                    <div class="title"><span>1</span>Asteroids Delux</div>
                                    <div class="cover">
                                        <div class="img-by img-by-5by2">
                                            <img src="{!! asset('/images/game-cover.jpg') !!}" alt="title">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="game">
                            <div class="game-border">
                                <div class="game-inner">
                                    <div class="title"><span>2</span>Asteroids Delux</div>
                                    <div class="cover">
                                        <div class="img-by img-by-5by2">
                                            <img src="{!! asset('/images/game-cover.jpg') !!}" alt="title">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="game">
                            <div class="game-border">
                                <div class="game-inner">
                                    <div class="title"><span>3</span>Asteroids Delux</div>
                                    <div class="cover">
                                        <div class="img-by img-by-5by2">
                                            <img src="/images/game-cover.jpg" alt="title">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="game">
                            <div class="game-border">
                                <div class="game-inner">
                                    <div class="title"><span>4</span>Asteroids Delux</div>
                                    <div class="cover">
                                        <div class="img-by img-by-5by2">
                                            <img src="/images/game-cover.jpg" alt="title">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="game">
                            <div class="game-border">
                                <div class="game-inner">
                                    <div class="title"><span>5</span>Asteroids Delux</div>
                                    <div class="cover">
                                        <div class="img-by img-by-5by2">
                                            <img src="/images/game-cover.jpg" alt="title">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tournamentRank deco">
                <div class="tournamentRank-inner">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">Place</th>
                                <th scope="col">Team Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Your Team</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Your Team</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Your Team</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">Place</th>
                                <th scope="col">Team Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">4</th>
                                <td>Your Team</td>
                            </tr>
                            <tr>
                                <th scope="row">5</th>
                                <td>Your Team</td>
                            </tr>
                            <tr>
                                <th scope="row">6</th>
                                <td>Your Team</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="export">
                    <button class="btn btn-border" type="button" id="search">
                        <div>
                            <i class="icon-log-out" aria-hidden="true"></i>
                            Export
                        </div>
                    </button>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@stop

@section('Js')

@stop