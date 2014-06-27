<?php
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
