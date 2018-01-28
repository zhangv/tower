<?php
require_once __DIR__ . '/../src/TowerClient.php';
use PHPUnit\Framework\TestCase;
use \zhangv\tower\TowerClient;
use \zhangv\tower\HttpClient;
class TowerClientTest extends TestCase{
	/** @var TowerClient */
	private $towerClient = null;

	public function setUp(){
		$cfg = [
			'clientId' => 'xxx',
			'clientSecret' => 'xxx',
			'redirectURI' => 'http://YOURSITE.com/demo/oauthcallback.php',
		];
		if(file_exists(__DIR__ . '/config.php')) $config = require __DIR__ . '/config.php';
		$this->towerClient = new TowerClient($cfg['clientId'],$cfg['clientSecret']);
		$token = '620aa3f9b396397b18cb4184ee8424bdc142cfcfd4fba6a68e1fdfe66a6a821d';
		$this->towerClient->setAccessToken($token);
	}

	public function testGetTeams(){
		$teams = $this->towerClient->getTeams();
		$this->assertNotNull($teams);
	}


}
