<?php
require_once './includes/common.php';

asort($livesites_array);
$top_livesites = arrayToObject($liveTopsites_array);
$livesites = arrayToObject($livesites_array);
$logpath = "./logs/control_panel_feed.log";
$logpath = "../logs/devsites.sitebase.log";
$logcontents = file_get_contents($logpath, true);

$old_format = objectToArray(json_decode($logcontents));

foreach ($old_format as $key => $value) 
{
	$new_format[strtolower($value['repo'])][strtolower($value['type'])][] = $value;
}
$local_sites = arrayToObject($new_format);

$webdevsites = array();

include 'public/html/status.phtml';

