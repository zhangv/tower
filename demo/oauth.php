<?php
require_once '../src/TowerClient.php';
require_once '../src/TowerOAuth.php';
use zhangv\tower\TowerClient;

$cfg = require './config.php';
$clientId = $cfg['clientId'];
$clientSecret = $cfg['clientSecret'];
$redirectURI = $cfg['redirectURI'];
$tc = new TowerClient($clientId,$clientSecret);
$to = $tc->authorizeURI($redirectURI);
$this->redirect($to);