<?php

namespace zhangv\tower;

class HttpClient {
	const GET = 'get',POST = 'post', DELETE = 'delete',PUT = 'put',PATCH = 'patch';
	private $instance;
	private $info;
	private $timeout = 1;

	public function __construct($timeout = 1) {
		$this->initInstance($timeout);
	}

	public function initInstance($timeout){
		if(!$this->instance) {
			$this->instance = curl_init();
			if ($timeout < 1) {
				curl_setopt($this->instance, CURLOPT_TIMEOUT_MS, intval($timeout * 1000));
			} else {
				curl_setopt($this->instance, CURLOPT_TIMEOUT, intval($timeout));
			}
			curl_setopt($this->instance, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($this->instance, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($this->instance, CURLOPT_SSL_VERIFYPEER, false);
		}
	}

	public function get($url,$params,$headers = array()) {
		if (!$this->instance)	$this->initInstance($this->timeout);
		$url .= '?' . http_build_query($params);
		curl_setopt($this->instance, CURLOPT_URL, $url);
		curl_setopt($this->instance, CURLOPT_HTTPGET, true);
		curl_setopt($this->instance, CURLOPT_HTTPHEADER, $headers);
		$result = $this->execute();
		curl_close($this->instance);
		$this->instance = null;
		return $result;
	}

	public function post($url, $params,$headers = array()) {
		if (!$this->instance)	$this->initInstance($this->timeout);
		curl_setopt($this->instance, CURLOPT_URL, $url);
		curl_setopt($this->instance, CURLOPT_POST, true);
		curl_setopt($this->instance, CURLOPT_POSTFIELDS, $params);
		curl_setopt($this->instance, CURLOPT_HTTPHEADER, $headers);
		$result = $this->execute();
		curl_close($this->instance);
		$this->instance = null;
		return $result;
	}

	public function delete($url, $params,$headers = array()) {
		if (!$this->instance)	$this->initInstance($this->timeout);
		curl_setopt($this->instance, CURLOPT_URL, $url);
		curl_setopt($this->instance, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($this->instance, CURLOPT_HEADER, false);
		curl_setopt($this->instance, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->instance, CURLOPT_HTTPHEADER, $headers);
		$result = $this->execute();
		curl_close($this->instance);
		$this->instance = null;
		return $result;
	}

	public function put($url, $params,$headers = array()) {
		if (!$this->instance)	$this->initInstance($this->timeout);
		curl_setopt($this->instance, CURLOPT_URL, $url);
		curl_setopt($this->instance, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($this->instance, CURLOPT_HEADER, false);
		curl_setopt($this->instance, CURLOPT_POSTFIELDS, $params);
		curl_setopt($this->instance, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->instance, CURLOPT_HTTPHEADER, $headers);
		$result = $this->execute();
		curl_close($this->instance);
		$this->instance = null;
		return $result;
	}

	public function patch($url, $params,$headers = array()) {
		if (!$this->instance)	$this->initInstance($this->timeout);
		curl_setopt($this->instance, CURLOPT_URL, $url);
		curl_setopt($this->instance, CURLOPT_CUSTOMREQUEST, "PATCH");
		curl_setopt($this->instance, CURLOPT_HEADER, false);
		curl_setopt($this->instance, CURLOPT_POSTFIELDS, $params);
		curl_setopt($this->instance, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->instance, CURLOPT_HTTPHEADER, $headers);
		$result = $this->execute();
		curl_close($this->instance);
		$this->instance = null;
		return $result;
	}

	private function execute() {
		$result = curl_exec($this->instance);
		if (curl_errno($this->instance))	{
			$result = false;
		}
		$this->info = curl_getinfo($this->instance);
		return $result;
	}

	public function setOpt($optArray) {
		if (!$this->instance)	return;
		if (!is_array($optArray))	throw new \Exception("Argument is not an array!");
		curl_setopt_array($this->instance, $optArray);
	}

	public function getInfo(){
		return $this->info;
	}
}
