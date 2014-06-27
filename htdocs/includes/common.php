<?php
date_default_timezone_set('Europe/London');

if(file_exists(dirname(__FILE__) . '/user.config.php')){
	require_once 'user.config.php';
}
else
{
	echo "Missing file <b>user.config.php</b><br>Create in includes dir. Please look at ./includes/user.config.example.php for more info";
	exit();
}

require_once 'config.php';
require_once 'common_functions.php';