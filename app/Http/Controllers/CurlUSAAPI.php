<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Session;
use DB;
use Curl;
use Request;
use Redirect;
use App;
use Cookie;

class CurlUSAAPI extends Controller
{
	private $url = '';

	public function __construct(){
		$curl = new Curl;
		$this->curl = $curl;
		$this->url = env('BRACKET_API_URL');
	}

	// ok
	public function viewEventConfigData($event_id){

		$this->curl->curl_type = 'get';
		$url_path = $this->url . 'events/' . $event_id;
		$response = $this->curl->curl($url_path, '');
		$data = json_decode($response, true);

		return $data;
	}

	// ok
	public function viewEventAllData(){

		$event_id = Request::get('event_id', '');
		$offset = Session::get('offset');

		$this->curl->curl_type = 'get';
		$url_path = $this->url . 'events/' . $event_id . '/bracket';
		$response = $this->curl->curl($url_path, '');

		$data = json_decode($response, true);

		if(empty($data['status'])){
			$now = time();
			$start_datetime = strtotime($data['start_datetime'])+$offset;
			$rounds_time = $data['round_duration_in_min'];
			$rounds_end_time = array();

			$config = $this->viewEventConfigData($event_id);
			$buffer = $config['end_of_round_buffer_duration_in_min'];

			foreach ($data['brackets'] as $key1 => $value1) {

				foreach ($value1['rounds'] as $key2 => $value2) {

					$data['brackets'][$key1]['rounds'][$key2]['branchs'] = array_chunk($value2['branchs'], 2);

					if(($key2) == 0){
					// 	$rounds_end_time[$key2] = $start_datetime;
						$rounds_end_time[$key2] = $start_datetime + (($key2+1) * $rounds_time * 60);
					}else{
						// $rounds_end_time[$key2] = $start_datetime + ((($key2+1) * $rounds_time) + ($buffer * ($key2+1)) * 60);
						$rounds_end_time[$key2] = $start_datetime + (($key2+1) * $rounds_time * 60) + ($key2 * $buffer * 60);
					}
				}
			}
			$data['rounds_end_time'] = $rounds_end_time;
			$result = json_encode($data);
		}else{
			$result = json_encode([]);
		}

		return $result;
	}

	public function viewEventAllTeam($event_id){

		$this->curl->curl_type = 'get';
		$url_path = $this->url . 'events/' . $event_id . '/teams';
		$response = $this->curl->curl($url_path, '');

		$fake = '[
				   {
				      "id":50,
				      "team_name":"Fudge",
				      "leader_arcade_net_id":gary.lin#1235,
				      "icon_id":56,
				      "members":[
				         {
				            "arcade_net_id":gary.lin#1235
				         }
				      ]
				   },
				   {
				      "id":51,
				      "team_name":"Oreo",
				      "leader_arcade_net_id":bob#35,
				      "icon_id":91,
				      "members":[
				         {
				            "arcade_net_id":bob#35
				         }
				      ]
				   },
				   {
				      "id":52,
				      "team_name":"Chip",
				      "leader_arcade_net_id":eric#5,
				      "icon_id":321,
				      "members":[
				         {
				            "arcade_net_id":eric#5
				         }
				      ]
				   },
				   {
				      "id":53,
				      "team_name":"Powder",
				      "leader_arcade_net_id":stewart#1,
				      "members":[
				         {
				            "arcade_net_id":stewart#1
				         }
				      ]
				   },
				   {
				      "id":54,
				      "team_name":"Extra Team",
				      "leader_arcade_net_id":john#12,
				      "icon_id":18,
				      "members":[
				         {
				            "arcade_net_id":john#12
				         }
				      ]
				   },
				   {
				      "id":55,
				      "team_name":"Doughnut",
				      "leader_arcade_net_id":max#12,
				      "icon_id":8,
				      "members":[
				         {
				            "arcade_net_id":max#12
				         },
				         {
				            "arcade_net_id":TheBest#10
				         },
				         {
				            "arcade_net_id":zelda#2
				         },
				         {
				            "arcade_net_id":wild#72
				         }
				      ]
				   }
				]
				';

		$data = json_decode($response, true);

		return $fake;
	}

	// ok
	public function createTeam(){

		$name = Request::get('name', '');
		$friend = Request::get('friend', '');
		$event_id = Request::get('form_event_id', '');
		$icon_id = Request::get('avatar_id', 1);
		$team_id = Request::get('team_id', '');

		if($team_id != ''){
			foreach ($friend as $key => $value) {
				if($value){
					$result = $this->addMember($event_id, $team_id, $value);
				}
			}
		}else{
			$this->curl->curl_type = 'post';
			$this->curl->setHeader('Content-Type:application/json')->setHeader('Authorization:AUTH_TOKEN_REPLACE_ME');
			$url_path = $this->url . 'events/' . $event_id . '/teams';

			$data['leader_uuid'] = Cookie::get('uuid');
			$data['username'] = Cookie::get('socialUserName');
			$data['team_name'] = $name;
			$data['icon_id'] = $icon_id;

			$response = $this->curl->curl($url_path, json_encode($data));
			$response_data = json_decode($response, true);

			if(empty($response_data['id'])){
				// 已經有隊伍
				return Redirect::back();
			}else{
				if($friend){
					foreach ($friend as $key => $value) {
						if($value){
							$result = $this->addMember($event_id, $response_data['id'], $value);
						}
					}
				}
			}
		}

		return Redirect::back();
	}

	// ok
	public function addMember($event_id, $team_id, $arcade_net_id){

		$result = App::make('App\Http\Controllers\CurlAPI')->findPotentialFriends($arcade_net_id);

		if($result != 0){
			$curl = new Curl;
			$curl->curl_type = 'post';
			$curl->setHeader('Content-Type:application/json')->setHeader('Authorization:AUTH_TOKEN_REPLACE_ME');
			$url_path = $this->url . 'events/' . $event_id . '/teams/' . $team_id . '/members';

			$response = $curl->curl($url_path, json_encode($result));
			$response_data = json_decode($response, true);

			return $response;
		}else{
			$friendsData = App::make('App\Http\Controllers\CurlAPI')->getFriends();
			$friendsDataDecode = json_decode($friendsData, true);

			$uuid = '';
			$user_name = '';
			foreach ($friendsDataDecode as $key => $value) {
				if(strpos($value['user_name'], $arcade_net_id) !== false){
					$uuid = $value['uuid'];
					$user_name = $value['user_name'];
				}
			}

			$arr['uuid'] = $uuid;
			$arr['username'] = $user_name;

			$curl = new Curl;
			$curl->curl_type = 'post';
			$curl->setHeader('Content-Type:application/json')->setHeader('Authorization:AUTH_TOKEN_REPLACE_ME');
			$url_path = $this->url . 'events/' . $event_id . '/teams/' . $team_id . '/members';

			$response = $curl->curl($url_path, json_encode($arr));
			$response_data = json_decode($response, true);

			return $response;
		}
	}

	// ok
	public function ownTeam($event_id){

		$uuid = Session::get('uuid');

		$this->curl->curl_type = 'get2';
		$url_path = $this->url . 'events/' . $event_id . '/team';
		$data['uuid'] = $uuid;
		$response = $this->curl->curl($url_path, $data);

		return $response;
	}

	// ok
	public function getSingleTeam($team_id){

		for($i=1; $i<=50; $i++){
			$this->curl->curl_type = 'get';
			$url_path = $this->url . 'teams/' . $team_id;
			$response = $this->curl->curl($url_path, '');
			$response_data = json_decode($response, true);

			if($i == 50){
				return false;
			}else if($response_data){
				break;
			}
		}

		return $response_data;
	}

}