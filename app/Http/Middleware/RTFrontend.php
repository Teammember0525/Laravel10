<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Cookie;
use Session;
use Request;

class RTFrontend
{

	public function handle($request, Closure $next)
	{
		$GLOBALS['model_list'] = App::make('App\Http\Controllers\Frontend\Device')->getgetModelList();

		if(Cookie::has('uuid')){
			Session::put('uuid', Cookie::get('uuid'));
		}

		if(Cookie::has('userName')){
			Session::put('userName', Cookie::get('userName'));
		}

		if(Cookie::has('socialUserName')){
			Session::put('socialUserName', Cookie::get('socialUserName'));
		}
		
		if(Cookie::has('token')){
			Session::put('token', Cookie::get('token'));
		}

		if(!Session::has('offset')){
			$ip=Request::ip();
	        $url = 'http://ip-api.com/json/' . $ip . '?fields=status,message,country,countryCode,region,regionName,city,zip,lat,lon,timezone,offset,isp,org,as,query';
	        $tz = file_get_contents($url);
	        $status = json_decode($tz,true)['status'];
	        if($status == 'success'){
	        	$offset = json_decode($tz,true)['offset'];
	        }else{
	        	$offset = 0;
	        }
	        Session::put('offset', $offset);
		}
		
		return $next($request);
	}
}
