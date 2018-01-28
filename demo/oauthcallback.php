<?php
require_once '../src/TowerClient.php';
require_once '../src/TowerOAuth.php';
use zhangv\tower\TowerClient;

$cfg = require './config.php';
$code = $_GET['code'];
$clientId = $cfg['clientId'];
$clientSecret = $cfg['clientSecret'];
$redirectURI = $cfg['redirectURI'];

$tc = new TowerClient($clientId,$clientSecret);
$r = $tc->authorize($code,$redirectURI);
$r = json_decode($r);
if(!empty($r->error)){
	$detail = print_r($r,true);
	echo "授权失败：{$r->error},$detail";
}else{
	echo '授权成功';
}