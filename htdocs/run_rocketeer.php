<?php
require_once 'includes/common.php';

$new_project = $path_bash_scripts . '/.newproject.sh';
$path_rocketer_bash = $_SERVER['DOCUMENT_ROOT'] . '/../get_servers_statuses.sh';
//Using MacScript, open new terminal and tell it to run .new_project.sh with appropiate params
// $a = shell_exec('osascript -e \'tell application "Terminal" activate\'');

$command = "osascript -e 'tell application \"iTerm\" to activate' -e 'tell application \"System Events\" to tell process \"iTerm\" to keystroke \"t\" using command down' -e 'tell application \"System Events\" to tell process \"iTerm\" to keystroke \"bash {$path_rocketer_bash}\"' -e 'tell application \"System Events\" to tell process \"iTerm\" to key code 52' ";

$status = exec($command);


// echo json_encode(array("status" => $status,
					   // "command" =>$cmd));