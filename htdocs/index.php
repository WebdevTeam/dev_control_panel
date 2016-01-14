<?php
require_once 'includes/common.php';

//asort($livesites_array);
$top_livesites = arrayToObject($liveTopsites_array);
$livesites = arrayToObject($livesites_array);

//Get contents of current feed
$devsites_path = $_SERVER['DOCUMENT_ROOT'] . "/../feeds/devsites";

if (!file_exists($devsites_path))
{
	touch ($devsites_path);
}

$devsites_contents = objectToArray(json_decode(file_get_contents($devsites_path, true)));

foreach ($devsites_contents as $key => $value)
{
	$contents_array[strtolower($value['repo'])][strtolower($value['type'])][] = $value;
}

$local_sites = arrayToObject($contents_array);
$webdevsites = array();

include 'html/status.phtml';

