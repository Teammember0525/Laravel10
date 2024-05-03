<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use Session;
use App;
use Request;

class Device extends Controller
{
	private $url_view = 'Frontend';
	private $url_action = 'Frontend\Device';

	public function __construct(){

	}

	public function pageDevice(){

		$keyword = Request::get('keyword', '');
		$series = Request::get('series', '');
		$prefix = Request::get('prefix', '');
		$series_name = '';

		// if($gameTitle != ''){
		// 	$search_result = App::make('App\Http\Controllers\CurlAPI')->searchLeaderboard($gameTitle);
		// 	$search_result = json_decode($search_result, true);
		// }else{
		// 	$search_result = [];
		// }

		$leaderboard_result = App::make('App\Http\Controllers\CurlAPI')->getLeaderboardList($series, $prefix, $keyword);
		$leaderboard_data = json_decode($leaderboard_result, true);

		foreach ($leaderboard_data as $key => $value) {
			if(!empty($leaderboard_data[$key]['name'])){
				if($leaderboard_data[$key]['rankings'] != []){
					foreach ($leaderboard_data[$key]['rankings'] as $key2 => $value2) {
						$leaderboard_data[$key]['rankings'][$key2]['score'] = number_format($value2['score']);
					}
				}
			}else{
				unset($leaderboard_data[$key]);
			}
		}

		$unique_array = $this->getgetModelList();
		foreach ($unique_array as $key2 => $value2) {

			if($key2 == $series){
				$series_name = $value2['series_name'];
			}
		}

		$domain = Request::root();
		if($domain == 'https://www.atgames.net' || $domain == 'http://acnet-lb.atgames.net' || $domain == 'https://acnet-lb.atgames.net'){
			$new_url = 'https://www.atgames.net/leaderboards/device';
		}else{
			$new_url = $this->url_action . '@pageDevice';
		}

		$array_data = [
			'series_name' => $series_name ?? '',
			'last_game_id' => last($leaderboard_data)['game_id'] ?? 0,
			'keyword' => $keyword,
			'series' => $series,
			'prefix' => $prefix,
			'url_action' => $new_url,
			'unique_array' => $this->getgetModelList(),
			'leaderboard_data' => $leaderboard_data,
			// 'search_result' => $search_result,
		];

		return view($this->url_view . '.device', $array_data);
	}

	public function pageDeviceTop50($game_id){

		$friends = Request::get('friends', '');
		$series = Request::get('series', '');
		$timeRange = Request::get('timeRange', '');

		if($friends){
			$result = App::make('App\Http\Controllers\CurlAPI')->friendsLeaderboard($game_id, $timeRange);
			$data = json_decode($result, true)[0];
		}else{
			$result = App::make('App\Http\Controllers\CurlAPI')->getLeaderboard($game_id, $series, $timeRange);
			$data = json_decode($result, true)[0];
		}

		if($data == null){
			return \Redirect::back();
		}

		foreach ($data['rankings'] as $key => $value) {
			$data['rankings'][$key]['created_at'] = date('m/d/Y', strtotime($value['created_at']));
			$data['rankings'][$key]['score'] = number_format($value['score']);

			if(strpos($value['series'], 'Ultimate')) {
				$data['rankings'][$key]['series'] = 'Ultimate';
			} else if(strpos($value['series'], 'Gamer')) {
				$data['rankings'][$key]['series'] = 'Gamer';
			} else if(strpos($value['series'], 'Flashback')) {
				$data['rankings'][$key]['series'] = 'Flashback';
			} else if(strpos($value['series'], 'Pinball')) {
				$data['rankings'][$key]['series'] = 'Pinball';
			} else if(strpos($value['series'], 'Connect')) {
				$data['rankings'][$key]['series'] = 'Connect';
			} else if(strpos($value['series'], 'Core')) {
				$data['rankings'][$key]['series'] = 'Core';
			}
		}
		$data['snapshot'] = date('m/d/Y', strtotime($data['snapshot']));
		if($data['rankings']){
			$King_of_the_Hill = intval((time() - strtotime($data['rankings'][0]['created_at']))/86400);
		}else{
			$King_of_the_Hill = 0;
		}

		$domain = Request::root();
		if($domain == 'https://www.atgames.net' || $domain == 'http://acnet-lb.atgames.net' || $domain == 'https://acnet-lb.atgames.net'){
			$new_url = 'https://www.atgames.net/leaderboards/device/top50/' . $game_id;
		}else{
			$new_url = $this->url_action . '@pageDeviceTop50';
		}

		$array_data = [
			'friends' => $friends,
			'game_id' => $game_id,
			'series' => $series,
			'timeRange' => $timeRange,
			'King_of_the_Hill' => $King_of_the_Hill,
			'url_action' => $new_url,
			'data' => $data,
			'unique_array' => $this->getgetModelList(),
		];

		return view($this->url_view . '.device_top50', $array_data);
	}

	public function getgetModelList(){

		$model_result = App::make('App\Http\Controllers\CurlAPI')->getModelList();
		$model_data = json_decode($model_result, true);

		$unique_array = array();
		if($model_data){
			foreach ($model_data as $key => $value) {

				if(strpos($value['series_name'], 'Ultimate')){
					$value['series_name'] = 'Ultimate';
				}else if(strpos($value['series_name'], 'Gamer')){
					$value['series_name'] = 'Gamer';
				}else if(strpos($value['series_name'], 'Flashback')){
					$value['series_name'] = 'Flashback';
				}else if(strpos($value['series_name'], 'Pinball')){
					$value['series_name'] = 'Pinball';
				}else if(strpos($value['series_name'], 'Connect')){
					$value['series_name'] = 'Connect';
				}else if(strpos($value['series_name'], 'Core')){
					$value['series_name'] = 'Core';
				}

				$unique_array[$value['series_id']] = [
					'default_icon' => $value['default_icon'],
					'series_id' => $value['series_id'],
					'series_name' => $value['series_name'],
				];
			}
		}

		return $unique_array;
	}
}
