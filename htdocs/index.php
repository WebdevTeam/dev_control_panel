<?php
require_once 'includes/common.php';

//asort($livesites_array);
$top_livesites = arrayToObject($liveTopsites_array);
$livesites = arrayToObject($livesites_array);
function csv_to_array($filename='', $delimiter=','){

	$assoc_array = array();
	if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE){
        	$assoc_array[$row[0]] = $row[1];
        }
    }
    
    return $assoc_array;
}
//Get contents of current feed
$feeds_path = $_SERVER['DOCUMENT_ROOT'] . "/../feeds/";

//Get contents of current feed
$devsites_path = $_SERVER['DOCUMENT_ROOT'] . "/../feeds/devsites";

$site_branches = csv_to_array($feeds_path . 'branches', ' ');
$site_tags = csv_to_array($feeds_path . 'tags', ' ');

if (!file_exists($devsites_path))
{
	touch ($devsites_path);
}

$devsites_contents = objectToArray(json_decode(file_get_contents($devsites_path, true)));
$contents_array = new stdClass();
if(is_array($devsites_contents))
foreach ($devsites_contents as $key => $value)
{
	$contents_array[strtolower($value['repo'])][strtolower($value['type'])][] = $value;
}

$local_sites = arrayToObject($contents_array);
$webdevsites = array();

include 'html/status.phtml';

