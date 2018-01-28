<?php
namespace zhangv\tower;

class TowerOAuth{
	private $endPoint = 'https://tower.im/oauth/';
	private $clientId = null;
	private $clientSecret = null;
	private $httpClient = null;

	public function __construct($clientId,$clientSecret){
		$this->clientId = $clientId;
		$this->clientSecret = $clientSecret;
		$this->httpClient = new HttpClient();
	}

	public function authorizeURI($redirectURI){
		$redirectURI= urlencode($redirectURI);
		return "{$this->endPoint}authorize?client_id={$this->clientId}&redirect_uri={$redirectURI}&response_type=code&scope=";
	}

	public function authorize($code,$redirectURI){
		$params = [
			'grant_type' => 'authorization_code',
			'client_id' => $this->clientId,
			'client_secret' => $this->clientSecret,
			'code' => $code,
			'redirect_uri' => $redirectURI];
		return $this->httpClient->post($this->endPoint . "token",$params);
	}

	public function revoke($token){
		$params = [
			'client_id' => $this->clientId,
			'client_secret' => $this->clientSecret,
			'token' => $token];
		return $this->httpClient->post($this->endPoint . "revoke",$params);
	}

	public function refresh($refreshToken){
		$params = ['grant_type' => 'refresh_token', 'client_id' => $this->clientId,
			'client_secret' => $this->clientSecret, 'refresh_token' => $refreshToken];
		return $this->httpClient->post($this->endPoint . "token",$params);
	}

	/**
	 * @deprecated
	 * @param $username
	 * @param $password
	 * @return bool|mixed
	 */
	public function password($username,$password){
		$params = ['grant_type' => 'password', 'username' => $username, 'password' => $password];
		return $this->httpClient->post($this->endPoint . "token",$params);
	}

	public function setEndPoint($endpoint){
		$this->endPoint = $endpoint;
	}
}
