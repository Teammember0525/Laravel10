<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use Session;
use App;


class Highscore extends Controller
{
	private $url_view = 'Frontend';
	private $url_action = 'Frontend\Highscore';

	public function __construct(){
		
	}

	public function pageHighScoreResult($event_id){
		$offset = Session::get('offset');

		$response = App::make('App\Http\Controllers\CurlAPI')->GetTournamentResult($event_id);
		$response_data = json_decode($response, true);

		$response_data['start'] = date('m/d/Y', strtotime($response_data['start'])+$offset);
		$response_data['end'] = date('m/d/Y', strtotime($response_data['end'])+$offset);

		$array_data = [
			'event_id' => $event_id,
			'response_data' => $response_data,
		];
		
		return view($this->url_view . '.highscore', $array_data);
	}

	public function pageHighScoreTop50($event_id){
		$offset = Session::get('offset');
		
		$response = App::make('App\Http\Controllers\CurlAPI')->GetTournamentResult($event_id);
		$response_data = json_decode($response, true);

		$response_data['start'] = date('m/d/Y', strtotime($response_data['start'])+$offset);
		$response_data['end'] = date('m/d/Y', strtotime($response_data['end'])+$offset);

		foreach ($response_data['games'] as $key => $value) {
			$new_rank = App::make('App\Http\Controllers\CurlAPI')->GetTournamentResult2($value['game_id'], $event_id);
			$new_rank_data = json_decode($new_rank, true);

			$response_data['games'][$key]['rankings'] = $new_rank_data['rankings'][0]['rankings'];
		}
		
		$array_data = [
			'event_id' => $event_id,
			'response_data' => $response_data,
		];

		return view($this->url_view . '.highscore_top50', $array_data);
	}
}