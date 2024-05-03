<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use Session;
use App;

class Index extends Controller
{
	private $url_view = 'Frontend';
	private $url_action = 'Frontend\Index';

	public function __construct(){
		
	}

	public function pageIndex(){

		$result = App::make('App\Http\Controllers\CurlAPI')->getTournamentList();
		$data = json_decode($result, true);
		$offset = Session::get('offset');

		$new_array['tournaments'] = array();
		foreach ($data['tournaments'] as $key => $value) {
			if($value['status'] == 'Upcoming' || $value['status'] == 'Active'){
				$data['tournaments'][$key]['start'] = date('m/d/Y', strtotime($value['start'])+$offset);
				$data['tournaments'][$key]['end'] = date('m/d/Y', strtotime($value['end'])+$offset);
				array_push($new_array['tournaments'], $data['tournaments'][$key]);
			}
		}

		$array_data = [
			'count' => count($new_array),
			'data' => $new_array,
		];

		return view($this->url_view . '.index', $array_data);
	}
}