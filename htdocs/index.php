<?php
date_default_timezone_set('Europe/London');

function objectToArray($d)
{
	if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}

	if (is_array($d)) {
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map(__FUNCTION__, $d);
	}
	else {
		// Return array
		return $d;
	}
}
function arrayToObject($d)
{
	if (is_array($d)) {
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return (object) array_map(__FUNCTION__, $d);
	}
	else {
		// Return object
		return $d;
	}
}
$trunk = array(
	"c" => array(
		"name" => "Central Admin",
		"trunk_url" => "http://svn.worldstores.co.uk/centraladmin/trunk",
		"branch_url" => "http://svn.worldstores.co.uk/centraladmin/branches/"
		),
	"k" => array(
		"name" => "SKUbase",
		"trunk_url" => "http://svn.worldstores.co.uk/skubase/trunk",
		"branch_url" => "http://svn.worldstores.co.uk/skubase/branches/"
		),
	"l" => array(
		"name" => "Logistic API",
		"trunk_url" => "http://svn.worldstores.co.uk/logisticsapi/trunk",
		"branch_url" => "http://svn.worldstores.co.uk/logisticsapi/branch/"
		),
	"p" => array(
		"name" => "Private Sale",
		"trunk_url" => "http://svn.worldstores.co.uk/privatesales/trunk",
		"branch_url" => "http://svn.worldstores.co.uk/privatesales/branches/"
		),
	"s" => array(
		"name" => "Sitebase",
		"trunk_url" => "http://svn.worldstores.co.uk/sitebase/trunk",
		"branch_url" => "http://svn.worldstores.co.uk/sitebase/branch/"
		),
	"t" => array(
		"name" => "TMO",
		"trunk_url" => "http://svn.worldstores.co.uk/tmo/trunk",
		"branch_url" => "http://svn.worldstores.co.uk/tmo/branches/"
		)
	);
$site_types = array(
	"c" => "Central Admin",
	"k" => "SKUbase",
	"l" => "Logistic",
	"p" => "Private Sale",
	"s" => "Sitebase",
	"t" => "TMO"
	);
$sub_user = "usman";
$sub_pass = "f4d3_2_bl4ck";
$liveTopsites_array = array(
	"CWS01" => array(
			"repo" => "http://svn.worldstores.co.uk/centraladmin/trunk",
			"url" => "cws01.worldstores.co.uk",
			"website_id" => 9201
		),
	"CWS02" => array(
			"repo" => "http://svn.worldstores.co.uk/centraladmin/trunk",
			"url" => "cws02.worldstores.co.uk",
			"website_id" => 9202
		)
	);
$livesites_array = array(
	"WorldStores" => array(
			"repo" => "http://svn.worldstores.co.uk/sitebase/trunk",
			"url" => "www.worldstores.co.uk",
			"website_id" => 69
		),
	"Casafina" => array(
			"repo" => "http://svn.worldstores.co.uk/privatesale/trunk",
			"url" => "www.casafina.com",
			"website_id" => 161
		)
	);
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

