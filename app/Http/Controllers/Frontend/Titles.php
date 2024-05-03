<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use Session;
use App;
use Request;

class Titles extends Controller
{
	private $url_view = 'Frontend';
	private $url_action = 'Frontend\Titles';

	public function __construct(){
		
	}

	public function pageTitles(){

		$rule = Request::get('rule', '');
		$prefix = Request::get('prefix', '');
		$order = Request::get('order', '');
		$friends = Request::get('friends', '');
		$table = Request::get('table', '');
		$table_rule = Request::get('table_rule', '');
		$keyword = Request::get('keyword', '');

		$title_result = App::make('App\Http\Controllers\CurlAPI')->getTitleLeaderboardList($rule, $prefix, $order, $friends, $table, $table_rule, $keyword);
		$title_data = json_decode($title_result, true);

		foreach ($title_data as $key => $value) {
			if(empty($title_data[$key]['game_id'])){
				unset($title_data[$key]);
			}
		}

		$domain = Request::root();
		if($domain == 'https://www.atgames.net' || $domain == 'http://acnet-lb.atgames.net' || $domain == 'https://acnet-lb.atgames.net'){
			$new_url = 'https://www.atgames.net/leaderboards/titles';
		}else{
			$new_url = $this->url_action . '@pageTitles';
		}

		$array_data = [
			'last_game_id' => last($title_data)['game_id'] ?? 0,
			'count' => count($title_data),
			'rule' => $rule,
			'prefix' => $prefix,
			'order' => $order,
			'friends' => $friends,
			'table' => $table,
			'table_rule' => $table_rule,
			'keyword' => $keyword,
			'url_action' => $new_url,
			'title_data' => $title_data,
		];

		return view($this->url_view . '.titles', $array_data);
	}
}