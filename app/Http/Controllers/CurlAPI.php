<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Session;
use DB;
use Curl;
use Request;
use Redirect;

class CurlAPI extends Controller
{
	private $url = "";

	public function __construct(){
		$curl = new Curl;
		$this->curl = $curl;
		$this->url = env('ARCADE_API_URL');
	}

	public function userLogin(){

		$email = Request::get('email', '');
		$password = Request::get('password', '');

		$this->curl->curl_type = 'post';
		$this->curl->setHeader('devise:Arcade')->setHeader('Content-Type:application/json')->setHeader('fp:Devise fingerprint');

		$url_path = $this->url . 'api/account/sign_in';
		$data['email'] = $email ?? ''; // rt.james.chang@gmail.com
		$data['password'] = $password ?? ''; // james666

		$response = $this->curl->curl($url_path, json_encode($data));
		$response_data = json_decode($response, true);

		if(empty($response_data['account'])){
			$back['status'] = 500;
			$back['response'] = $response_data;
			// 失敗訊息
			// return Redirect::back()->withErrors($response['failureError']['message']);
			// return json_encode($response_data['failureError']['message']);
			return json_encode($back);
		}else{

			$back['status'] = 200;
			$back['response'] = $response_data;
			// Session::put('uuid', $response_data['account']['uuid']);
			// Session::put('userName', $response_data['account']['userName']);
			// Session::put('socialUserName', $response_data['account']['socialUserName']);
			// Session::put('token', $response_data['account']['token']);

			return json_encode($back);
		}

		// return Redirect::back();
	}

	public function userLogout(){

		$this->curl->curl_type = 'delete';
		$this->curl->setHeader('devise:Arcade')->setHeader('Content-Type:application/json')->setHeader('fp:Devise fingerprint')->setHeader('authorization:Bearer ' . Session::get('token'));

		$url_path = $this->url . 'api/account/logout';
		$response = $this->curl->curl($url_path, '');

		Session::forget('uuid');
		Session::forget('userName');
		Session::forget('socialUserName');
		Session::forget('token');

		return 200;
	}

	public function getFriends(){

		$this->curl->curl_type = 'get';
		$this->curl->setHeader('devise:Arcade')->setHeader('Content-Type:application/json')->setHeader('fp:Devise fingerprint')->setHeader('authorization:Bearer ' . Session::get('token'));

		$url_path = $this->url . 'd2d/arcade/v1/friends';
		$response = $this->curl->curl($url_path, '');

		return $response;
	}

	public function getInvitation(){

		$this->curl->curl_type = 'get';
		$this->curl->setHeader('devise:Arcade')->setHeader('Content-Type:application/json')->setHeader('fp:Devise fingerprint')->setHeader('authorization:Bearer ' . Session::get('token'));

		$url_path = $this->url . 'd2d/arcade/v1/friend_requests';
		$response = $this->curl->curl($url_path, '');

		return $response;
	}

	public function getBlacklist(){

		$this->curl->curl_type = 'get';
		$this->curl->setHeader('devise:Arcade')->setHeader('Content-Type:application/json')->setHeader('fp:Devise fingerprint')->setHeader('authorization:Bearer ' . Session::get('token'));

		$url_path = $this->url . 'd2d/arcade/v1/friends/black_list';
		$response = $this->curl->curl($url_path, '');

		return $response;
	}

	public function findPotentialFriends($id){

		$curl = new Curl;
		$this->curl->curl_type = 'post';
		$this->curl->setHeader('devise:Arcade')->setHeader('Content-Type:application/json')->setHeader('fp:Devise fingerprint')->setHeader('authorization:Bearer ' . Session::get('token'));

		$url_path = $this->url . 'd2d/arcade/v1/friend_requests/potential_friends';
		$data['q'] = $id;
		// $data['q'] = 'shawntseng40+001@gmail.com#8519';

		$response = $this->curl->curl($url_path, json_encode($data));
		$decode_data = json_decode($response, true);

		if($decode_data){
			// $uuid = array_shift($decode_data)['uuid'];
			$arr['uuid'] = $decode_data[0]['uuid'];
			$arr['username'] = $decode_data[0]['user_name'];
			return $arr;
		}else{
			return 0;
		}
	}

	public function addFriends(){

		$this->curl->curl_type = 'post';
		$this->curl->setHeader('devise:Arcade')->setHeader('Content-Type:application/json')->setHeader('fp:Devise fingerprint')->setHeader('authorization:Bearer ' . Session::get('token'));

		$url_path = $this->url . 'd2d/arcade/v1/friend_requests';
		$data['account_id'] = '630';

		$response = $this->curl->curl($url_path, json_encode($data));

		return $response;
	}

	public function updateFriendship(){

		$this->curl->curl_type = 'post';
		$this->curl->setHeader('devise:Arcade')->setHeader('Content-Type:application/json')->setHeader('fp:Devise fingerprint')->setHeader('authorization:Bearer ' . Session::get('token'));

		$url_path = $this->url . 'd2d/arcade/v1/friends/react';
		$data['account_id'] = '630';
		$data['react'] = 'block';

		$response = $this->curl->curl($url_path, json_encode($data));

		return $response;
	}

	public function getAvatars(){

		$this->curl->curl_type = 'get';
		$this->curl->setHeader('devise:Arcade')->setHeader('Content-Type:application/json')->setHeader('fp:Devise fingerprint')->setHeader('authorization:Bearer ' . Session::get('token'));

		$url_path = $this->url . 'api/medias';
		$response = $this->curl->curl($url_path, '');

		return $response;
	}

	public function uploadAvatars(){

		$this->curl->curl_type = 'post';
		$this->curl->setHeader('devise:Arcade')->setHeader('Content-Type:application/json')->setHeader('fp:Devise fingerprint')->setHeader('authorization:Bearer ' . Session::get('token'));

		$url_path = $this->url . 'api/medias';
		$data['file'] = '123.png';

		$response = $this->curl->curl($url_path, json_encode($data));

		return $response;
	}

	public function updateAccount(){

		$this->curl->curl_type = 'post';
		$this->curl->setHeader('devise:Arcade')->setHeader('Content-Type:application/json')->setHeader('fp:Devise fingerprint')->setHeader('authorization:Bearer ' . Session::get('token'));

		$url_path = $this->url . 'api/account/update';
		$data['user_name'] = 'arcade';

		$response = $this->curl->curl($url_path, json_encode($data));

		return $response;
	}

	public function getTournamentCalendar(){

		$this->curl->curl_type = 'get';

		$url_path = $this->url . 'd2d/arcade/v2/leaderboards/tournaments';
		$response = $this->curl->curl($url_path, '');

		return $response;
	}

	public function getTournamentList(){

		$this->curl->curl_type = 'get';

		$url_path = $this->url . 'd2d/arcade/v1/leaderboards/tournaments';
		$response = $this->curl->curl($url_path, '');

		return $response;
	}

	public function getTournament($tournament_id){

		$this->curl->curl_type = 'get';

		$url_path = $this->url . 'd2d/arcade/v2/leaderboards/tournaments/' . $tournament_id;
		$response = $this->curl->curl($url_path, '');

		return $response;
	}

	public function getModelList(){

		$this->curl->curl_type = 'get';

		$url_path = $this->url . 'd2d/arcade/v2/hardware_models';
		$response = $this->curl->curl($url_path, '');

		return $response;
	}

	public function getLeaderboardList(){

		$series = Request::get('series', '');
		$prefix = Request::get('prefix', '');
		$keyword = Request::get('keyword', '');
		if($series == ''){
			$url_parameter = 'model=all';
			if($prefix != ''){
				$url_parameter = $url_parameter . '&prefix=' . $prefix;
			}
			if($keyword != ''){
				$keyword = str_replace(' ', '+', $keyword);
				$url_parameter = $url_parameter . '&keyword=' . $keyword;
			}
		}else{
			$url_parameter = http_build_query(Request::all());
		}

		$url_parameter = $url_parameter . '&limit=8';

		$this->curl->curl_type = 'get';
		$url_path = $this->url . 'd2d/arcade/v2/leaderboards?' . $url_parameter;
		$response = $this->curl->curl($url_path, '');

		return $response;
	}

	public function getLeaderboardAfter(){

		$series = Request::get('series', '');
		$prefix = Request::get('prefix', '');
		$after = Request::get('after', '');
		$keyword = Request::get('keyword', '');

		if($series == ''){
			$url_parameter = 'model=all';
			if($prefix != ''){
				$url_parameter = $url_parameter . '&prefix=' . $prefix;
			}
			if($keyword != ''){
				$url_parameter = $url_parameter . '&keyword=' . $keyword;
			}
			$url_parameter = $url_parameter . '&after=' . $after;
		}else{
			$url_parameter = http_build_query(Request::all());
		}

		$url_parameter = $url_parameter . '&limit=8';

		$this->curl->curl_type = 'get';
		$url_path = $this->url . 'd2d/arcade/v2/leaderboards?' . $url_parameter;
		$response = $this->curl->curl($url_path, '');

		$response_data = json_decode($response, true);
		foreach ($response_data as $key => $value) {
			if(!empty($response_data[$key]['name'])){
				if($response_data[$key]['rankings'] != []){
					foreach ($response_data[$key]['rankings'] as $key2 => $value2) {
						$response_data[$key]['rankings'][$key2]['score'] = number_format($value2['score']);
					}
				}
			}else{
				unset($response_data[$key]);
			}
		}
		$encode = json_encode($response_data);

		return $encode;
	}

	public function getLeaderboard($game_id){

		$series = Request::get('series', '');
		$timeRange = Request::get('timeRange', '');
		if($series == ''){
			$url_parameter = 'model=all&timeRange=' . $timeRange;
		}else{
			$url_parameter = http_build_query(Request::all());
		}

		$this->curl->curl_type = 'get2';
		$url_path = $this->url . 'd2d/arcade/v2/leaderboards/' . $game_id . '/ranking?' . $url_parameter;

		$response = $this->curl->curl($url_path, '');

		return $response;
	}

	public function getUserHeightScore(){

		$this->curl->curl_type = 'get';
		$this->curl->setHeader('Authorization:Bearer ' . Session::get('token'));

		$url_path = $this->url . 'd2d/arcade/v2/leaderboards/personal';
		$response = $this->curl->curl($url_path, '');

		return $response;
	}

	public function searchLeaderboard(){

		$gameTitle = Request::get('gameTitle', '');

		$this->curl->curl_type = 'post';
		$this->curl->setHeader('devise:Arcade')->setHeader('Content-Type:application/json')->setHeader('fp:Devise fingerprint');

		$url_path = $this->url . '/d2d/arcade/v2/leaderboards/search';
		$data['gameTitle'] = $gameTitle ?? '';

		$response = $this->curl->curl($url_path, json_encode($data));

		return $response;
	}

	public function friendsLeaderboard($game_id){

		$timeRange = Request::get('timeRange', '');

		if($timeRange){
			$url_parameter = '?timeRange=' . $timeRange;
		}else{
			$url_parameter = '';
		}

		$this->curl->curl_type = 'get';
		$this->curl->setHeader('devise:Arcade')->setHeader('Content-Type:application/json')->setHeader('fp:Devise fingerprint')->setHeader('authorization:Bearer ' . Session::get('token'));

		$url_path = $this->url . 'd2d/arcade/v2/leaderboards/' . $game_id . '/friends' . $url_parameter;
		$response = $this->curl->curl($url_path, '');

		return $response;
	}

	public function getTitleLeaderboardList(){

		$rule = Request::get('rule', '');
		$prefix = Request::get('prefix', '');
		$order = Request::get('order', '');
		$friends = Request::get('friends', '');
		$table = Request::get('table', '');
		$table_rule = Request::get('table_rule', '');
		$keyword = Request::get('keyword', '');

		if($rule != ''){

			if($rule == 'buildin'){

				$url_parameter = urlencode('match[][context]') . '=tags&' . urlencode('match[][rule]') . '=AND&' . urlencode('match[][tags][]') . '=buildin';
			}else if($rule == 'NOT'){

				$url_parameter = urlencode('match[][context]') . '=tags&' . urlencode('match[][rule]') . '=AND&' . urlencode('match[][tags][]') . '=arcadenet';
			}else if($rule == 'AND'){

				$url_parameter = urlencode('match[][context]') . '=tags&' . urlencode('match[][rule]') . '=' . $rule . '&' . urlencode('match[][tags][]') . '=pinball';
			}

			if($prefix != ''){
				$url_parameter = $url_parameter . '&prefix=' . $prefix;
			}
			if($order != ''){
				$url_parameter = $url_parameter . '&order=' . $order;
			}
			if($friends == 1){
				$url_parameter = $url_parameter . '&friends=' . $friends;
			}
			if($keyword != ''){
				$keyword = str_replace(' ', '+', $keyword);
				$url_parameter = $url_parameter . '&keyword=' . $keyword;
			}
		}else{
			$url_parameter = 'model=all&' . http_build_query(Request::all());
		}

		// table
		if($table == 'buildin'){

			$url_parameter = $url_parameter . '&' . urlencode('match[][context]') . '=tags&' . urlencode('match[][rule]') . '=AND&' . urlencode('match[][tags][]') . '=buildin';
		}else if($table == 'steam'){

			if($table_rule == 'NOT'){
				$url_parameter = $url_parameter . '&' . urlencode('match[][context]') . '=tags&' . urlencode('match[][rule]') . '=AND&' . urlencode('match[][tags][]') . '=cloud&model=STREAMING';
			}else if($table_rule == 'AND'){
				$url_parameter = $url_parameter . '&' . urlencode('match[][context]') . '=tags&' . urlencode('match[][rule]') . '=' . $table_rule . '&' . urlencode('match[][tags][]') . '=steam&model=STREAMING';
			}else if($table_rule == 'all'){
				$url_parameter = $url_parameter . '&model=all';
			}
		}

		$url_parameter = $url_parameter . '&limit=8';

		$this->curl->curl_type = 'get';
		$url_path = $this->url . 'd2d/arcade/v2/leaderboards?' . $url_parameter;
		$response = $this->curl->curl($url_path, '');

		return $response;
	}

	public function getTitleLeaderboardAfter(){

		$rule = Request::get('rule', '');
		$prefix = Request::get('prefix', '');
		$order = Request::get('order', '');
		$friends = Request::get('friends', '');
		$table = Request::get('table', '');
		$table_rule = Request::get('table_rule', '');
		$after = Request::get('after', '');

		if($rule != ''){

			if($rule == 'buildin'){

				$url_parameter = urlencode('match[][context]') . '=tags&' . urlencode('match[][rule]') . '=AND&' . urlencode('match[][tags][]') . '=buildin';
			}else if($rule == 'NOT'){

				$url_parameter = urlencode('match[][context]') . '=tags&' . urlencode('match[][rule]') . '=AND&' . urlencode('match[][tags][]') . '=arcadenet';
			}else if($rule == 'AND'){

				$url_parameter = urlencode('match[][context]') . '=tags&' . urlencode('match[][rule]') . '=' . $rule . '&' . urlencode('match[][tags][]') . '=pinball';
			}
			if($prefix != ''){
				$url_parameter = $url_parameter . '&prefix=' . $prefix;
			}
			if($order != ''){
				$url_parameter = $url_parameter . '&order=' . $order;
			}
			if($friends == 1){
				$url_parameter = $url_parameter . '&friends=' . $friends;
			}
		}else{
			$url_parameter = 'model=all&' . http_build_query(Request::all());
		}

		// table
		if($table == 'buildin'){

			$url_parameter = $url_parameter . '&' . urlencode('match[][context]') . '=tags&' . urlencode('match[][rule]') . '=AND&' . urlencode('match[][tags][]') . '=buildin';
		}else if($table == 'steam'){

			if($table_rule == 'NOT'){
				$url_parameter = $url_parameter . '&' . urlencode('match[][context]') . '=tags&' . urlencode('match[][rule]') . '=AND&' . urlencode('match[][tags][]') . '=cloud&model=STREAMING';
			}else if($table_rule == 'AND'){
				$url_parameter = $url_parameter . '&' . urlencode('match[][context]') . '=tags&' . urlencode('match[][rule]') . '=' . $table_rule . '&' . urlencode('match[][tags][]') . '=steam&model=STREAMING';
			}else if($table_rule == 'all'){
				$url_parameter = $url_parameter . '&model=all';
			}
		}

		$url_parameter = $url_parameter . '&limit=8&after=' . $after;

		$this->curl->curl_type = 'get';
		$url_path = $this->url . 'd2d/arcade/v2/leaderboards?' . $url_parameter;
		$response = $this->curl->curl($url_path, '');

		$response_data = json_decode($response, true);
		$encode = json_encode($response_data);

		return $encode;
	}

	public function GetTournamentResult($event_id){

		$this->curl->curl_type = 'get';
		$url_path = $this->url . 'd2d/arcade/v2/leaderboards/tournaments/' . $event_id;
		$response = $this->curl->curl($url_path, '');

		return $response;
	}

	public function GetTournamentResult2($game_id, $event_id){

		$this->curl->curl_type = 'get';
		$url_path = $this->url . 'd2d/arcade/v2/leaderboards/' . $game_id . '/tournaments/' . $event_id;
		$response = $this->curl->curl($url_path, '');

		return $response;
	}
}