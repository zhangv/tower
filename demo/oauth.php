<?php
require_once 'autoload.php';

use zhangv\tower\TowerApiClient;

$cfg = require './config.php';
$clientId = $cfg['clientId'];
$clientSecret = $cfg['clientSecret'];
$redirectURI = $cfg['redirectURI'];
$tc = new TowerApiClient($clientId,$clientSecret);
$to = $tc->authorizeURI($redirectURI);
$this->redirect($to);