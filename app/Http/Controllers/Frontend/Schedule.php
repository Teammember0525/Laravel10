<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use Session;
use App;
use Request;


class Schedule extends Controller
{
	private $url_view = 'Frontend';
	private $url_action = 'Frontend\Schedule';

	public function __construct(){
		
	}

	public function pageSchedule(){

		$rule = Request::get('rule', '');
		$offset = Session::get('offset');

		$result = App::make('App\Http\Controllers\CurlAPI')->getTournamentList();
		$data = json_decode($result, true);
		$cc = collect($data['tournaments'])->sortByDesc('end');
		$data['tournaments'] = $cc->values()->all();

		$newest_event_id = 0;
		$new_array_data['tournaments'] = array();
		foreach ($data['tournaments'] as $key => $value) {
			// 篩選
			if($rule != ''){
				if(strtolower($value['status']) == $rule){
					$data['tournaments'][$key]['start'] = date('m/d/Y', strtotime($value['start'])+$offset);
					$data['tournaments'][$key]['end'] = date('m/d/Y', strtotime($value['end'])+$offset);

					if($new_array_data['tournaments'] == []){
						// 遊戲圖片
						$newest_event_id = $data['tournaments'][$key]['id'];
						$img_data = App::make('App\Http\Controllers\CurlAPI')->getTournament($data['tournaments'][$key]['id']);
						$decode_img_data = json_decode($img_data, true);
						if(empty($decode_img_data['games'])){
							$data['tournaments'][$key]['info']['games'] = [];
						}else{
							$data['tournaments'][$key]['info'] = $decode_img_data;
						}
						// 報名活動
						$team_info = App::make('App\Http\Controllers\CurlUSAAPI')->ownTeam($data['tournaments'][$key]['id']);
						$check = json_decode($team_info, true);
						if(empty($check['status'])){
							$data['tournaments'][$key]['team_info'] = 1;
						}else{
							$data['tournaments'][$key]['team_info'] = 0;
						}
					}

					array_push($new_array_data['tournaments'], $data['tournaments'][$key]);
				}
			}else{
				$data['tournaments'][$key]['start'] = date('m/d/Y', strtotime($value['start'])+$offset);
				$data['tournaments'][$key]['end'] = date('m/d/Y', strtotime($value['end'])+$offset);

				if($new_array_data['tournaments'] == []){
					// 遊戲圖片
					$newest_event_id = $data['tournaments'][$key]['id'];
					$img_data = App::make('App\Http\Controllers\CurlAPI')->getTournament($data['tournaments'][$key]['id']);
					$decode_img_data = json_decode($img_data, true);
					if(empty($decode_img_data['games'])){
						$data['tournaments'][$key]['info']['games'] = [];
					}else{
						$data['tournaments'][$key]['info'] = $decode_img_data;
					}
					// 報名活動
					$team_info = App::make('App\Http\Controllers\CurlUSAAPI')->ownTeam($data['tournaments'][$key]['id']);
					$check = json_decode($team_info, true);
					if(empty($check['status'])){
						$data['tournaments'][$key]['team_info'] = 1;
					}else{
						$data['tournaments'][$key]['team_info'] = 0;
					}
				}

				array_push($new_array_data['tournaments'], $data['tournaments'][$key]);
			}
		}

		$array_data = [
			'rule' => $rule,
			'tournaments_data' => $new_array_data,
			'tournaments_data_encode' => json_encode($new_array_data),
			'newest_event_id' => $newest_event_id,
			'had_login' => Session::has('token'),
		];

		return view($this->url_view . '.schedule', $array_data);
	}

	public function pageScheduleCalendar(){

		$result = App::make('App\Http\Controllers\CurlAPI')->getTournamentList();
		$result = str_replace('\"', ' ', $result);
		$data = json_decode(str_replace('\n', ' ', $result), true);
		$offset = Session::get('offset');

		$new_data = array();
		foreach ($data['tournaments'] as $key => $value) {
			$processing = array();
			$processing['event_id'] = $value['id'];
			$processing['style'] = $value['style'];
			$processing['title'] = $value['name'];
			$processing['description'] = $value['description'];
			$processing['start'] = date('Y-m-d', strtotime($value['start'])+$offset);
			$processing['end'] = date('Y-m-d', strtotime($value['end'])+$offset);
			$processing['className'] = 'fc-bg-' . strtolower($value['status']);
			$processing['status'] = strtolower($value['status']);
			$processing['big_status'] = $value['status'];
			$processing['modal_start'] = date('m/d/Y', strtotime($value['start'])+$offset);
			$processing['modal_end'] = date('m/d/Y', strtotime($value['end'])+$offset);
			$processing['eventConfigUrl'] = action('CurlUSAAPI@viewEventAllData');

			$new_data[] = $processing;
		}

		$time_now = date('F d, Y', time());

		$array_data = [
			'tournaments_data' => json_encode($new_data),
			'time_now' => $time_now,
			'had_login' => Session::has('token'),
		];

		return view($this->url_view . '.schedule_calendar', $array_data);
	}
}