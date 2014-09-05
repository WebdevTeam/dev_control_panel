<?php
require_once './includes/common.php';

$project = $path_bash_scripts . '/.branch_list.sh';
$repo = $_POST['repo'];

$sentinel_file = $_SERVER["DOCUMENT_ROOT"] . '../logs/update_complete';

//Remove any previous sentinels

if(file_exists($sentinel_file))
{
	unlink($sentinel_file);
}

//Using MacScript, open new terminal and tell it to run .branch_list.sh with appropiat params
exec('osascript -e \'tell application "Terminal" to do script "bash ' . $project . ' -r ' . $repo . '"\'');

$timeout = 20;
$try = 0;
do
{
	if(!file_exists($sentinel_file))
	{
		sleep(1);
		$try++;
	}
	else
	{
		unlink($sentinel_file);
		break;
	}
} while($try <= $timeout);

if ($try >= $timeout)
{
	echo "failed";
}
else
{
	echo "completed";
}