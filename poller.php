<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

require_once('library/EA/AutoLoader.php');
EA_AutoLoader::init(array(), 'library/');

$oHandler = new EA_Poller_Handler();
$oHandler->handle();