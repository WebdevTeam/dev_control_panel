<?php
require_once './includes/common.php';

$project = $path_bash_scripts . 'webdev-control-scripts/.branch_list.sh';
$repo = $_POST['repo'];
//Using MacScript, open new terminal and tell it to run .endproject.sh with appropiat params
echo exec('osascript -e \'tell application "Terminal" to do script "bash ' . $project . ' -r ' . $repo . '"\'');