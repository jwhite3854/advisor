<?php

define('APP_ROOT', dirname(__FILE__));
define('CORE_PATH', APP_ROOT.'/core');

// Get the bootstrap file to get this going
require CORE_PATH . '/bootstrap.php';

// Are we in dev mode?
$is_dev_mode = ( $_GET['dev_mode'] == 1 );

// Get all those site-wide config settings
AdvisorApp::setConfigs();

// Run the Advisor App
AdvisorApp::run( $_SERVER["REQUEST_URI"], $is_dev_mode );
