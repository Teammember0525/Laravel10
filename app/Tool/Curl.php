<?php

namespace App\Tool;

class Curl
{
	public $curl_type = '';
	
	private $header = [];

	public function curl($url, $data)
	{
		if(!$this->curl_type) return false;

		$curl = curl_init();

		switch($this->curl_type){
			case 'get':
				$url = $url . '?' . $data;
				break;
			case 'get2':
				curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'GET' );
				curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
				break;
			case 'post':
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case 'delete':
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
				break;
			default:
				return false;
				break;
		}

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);

		$result = curl_exec($curl);

		curl_close($curl);
		
		return $result;
	}

	public function setHeader($header){
		if($header) $this->header[] = $header;
		return $this;
	}
}
