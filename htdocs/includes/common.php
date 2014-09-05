<?php
date_default_timezone_set('Europe/London');

if(file_exists(dirname(__FILE__) . '/user.config.php'))
{
	require_once 'user.config.php';
}
else
{
	echo "Missing file <b>user.config.php</b><br>Please create that file in the includes/ dir. <br>For a reference look at /includes/user.config.example.php";
	exit();
}
$path_bash_scripts = rtrim($path_bash_scripts, "/");

require_once 'config.php';
require_once 'common_functions.php';