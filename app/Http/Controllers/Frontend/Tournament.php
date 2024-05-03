<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use Session;
use App;
use Request;

class Tournament extends Controller
{
	private $url_view = 'Frontend';
	private $url_action = 'Frontend\Tournament';

	public function __construct(){
		
	}

	public function pageTournamentResult($event_id){
		$offset = Session::get('offset');

		$response = App::make('App\Http\Controllers\CurlAPI')->GetTournamentResult($event_id);
		$response_data = json_decode($response, true);

		$response_data['start'] = date('m/d/Y', strtotime($response_data['start'])+$offset);
		$response_data['end'] = date('m/d/Y', strtotime($response_data['end'])+$offset);

		$array_data = [
			'event_id' => $event_id,
			'response' => json_encode($response_data['games']),
			'response_data' => $response_data,
		];

		return view($this->url_view . '.tournament_complete', $array_data);
	}

	public function pageTournamentBrackrt($event_id){
		$offset = Session::get('offset');

		$response = App::make('App\Http\Controllers\CurlAPI')->GetTournamentResult($event_id);
		$response_data = json_decode($response, true);

		$response_data['start'] = date('m/d/Y', strtotime($response_data['start'])+$offset);
		$response_data['end'] = date('m/d/Y', strtotime($response_data['end'])+$offset);

		$array_data = [
			'event_id' => $event_id,
			'response_data' => $response_data,
		];

		return view($this->url_view . '.tournament_brackrt', $array_data);
	}
}