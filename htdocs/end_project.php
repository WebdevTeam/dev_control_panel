<?php
require_once 'includes/common.php';

$end_project = $path_bash_scripts . '/.endproject.sh';
$delete_port = (int)$_POST['port'];
//Using MacScript, open new terminal and tell it to run .endproject.sh with appropiat params
echo exec('osascript -e \'tell application "Terminal" to do script "sudo bash ' . $end_project . ' -p ' . $delete_port . '"\'');