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

$liveTopsites_array[] = array(
	"CWS01" => array(
			"repo" => "http://svn.worldstores.co.uk/centraladmin/trunk",
			"url" => "cws01.worldstores.co.uk",
			"website_id" => 9201,
			"server" => array(
					'centraladmin'
				)
		)
	);

$livesites_array[] = array(
	"Kiddicare" => array(
			"repo" => "https://bitbucket.org/worldstores/skubase",
			"url" => "www.kiddicare.com",
			"website_id" => 164,
			"order_prefix" => 'WK',
			"server" => array(
					'kiddicare1'=>array(
							"webpark" => '4',
							'name'=>'web111',
							'ip'=>array(
									'internal' => '172.20.37.1',
									'external' => '109.71.125.90:2203'
								)
						),
					'kiddicare2'=>array(
							"webpark" => '4',
							'name'=>'web112',
							'ip'=>array(
									'internal' => '172.20.37.2',
									'external' => '109.71.125.90:2204'
								)
						)
					,'kiddicare3'=>array(
							"webpark" => '4',
							'name'=>'web113',
							'ip'=>array(
									'internal' => '172.20.37.3',
									'external' => '109.71.125.90:2205'
								)
						),
					'kiddicare-ssl'=>array(
							"webpark" => '4',
							'name'=>'web110',
							'ip'=>array(
									'internal' => '172.20.37.10',
									'external' => '109.71.125.90:2206'
								)
						),
					'kiddicare-redis'=>array(
							"webpark" => '4',
							'name'=>'res006',
							'ip'=>array(
									'internal' => '172.20.37.11',
									'external' => '109.71.125.92'
								)
						)
				)
		));
$livesites_array[] = array(
	"WorldStores" => array(
			"repo" => "https://bitbucket.org/worldstores/skubase",
			"url" => "www.worldstores.co.uk",
			"website_id" => 69,
			"order_prefix" => 'WS',
			"server" => array(
					'ws1'=>array(
							"webpark" => '5',
							'name'=>'web116',
							'ip'=>array(
									'internal' => '172.20.38.1',
									'external' => '217.169.62.238:2201'
								)
						),
					'ws2'=>array(
							"webpark" => '5',
							'name'=>'web117',
							'ip'=>array(
									'internal' => '172.20.38.2',
									'external' => '217.169.62.238:2202'
								)
						),
					'ws3'=>array(
							"webpark" => '5',
							'name'=>'web118',
							'ip'=>array(
									'internal' => '172.20.38.3',
									'external' => '217.169.62.238:2203'
								)
						),
					'wsapi1'=>array(
							"webpark" => '5',
							'name'=>'res036',
							'ip'=>array(
									'internal' => '172.20.38.47',
									'external' => '217.169.62.238:2221'
								)
						),
					'wsapi2'=>array(
							"webpark" => '5',
							'name'=>'res037',
							'ip'=>array(
									'internal' => '172.20.38.48',
									'external' => '217.169.62.238:2222'
								)
						)
				)
		));

$livesites_array[] = array(
	"BedroomWorld" => array(
			"repo" => "https://bitbucket.org/worldstores/skubase",
			"url" => "www.bedroomworld.co.uk",
			"website_id" => 19,
			"order_prefix" => 'BW',
			"webpark" => '2',
			"server" => array(
					'bedroomworld'=>array(
							"webpark" => '2',
							'name'=>'web027',
							'ip'=>array(
									'internal' => '172.20.32.33',
									'external' => '85.92.209.218'
								)
						)
				)
		)
	);

$livesites_array[] = array(
	"BedStore" => array(
			"repo" => "https://bitbucket.org/worldstores/skubase",
			"url" => "www.bedstore.co.uk",
			"website_id" => 57,
			"order_prefix" => 'BN',
			"webpark" => '2',
			"server" => array(
					'bedstore'=>array(
							"webpark" => '2',
							'name'=>'web036',
							'ip'=>array(
									'internal' => '172.20.32.31',
									'external' => '85.92.209.205'
								)
						)
				)
		)
	);

$livesites_array[] = array(
	"CagesWorld" => array(
			"repo" => "https://bitbucket.org/worldstores/skubase",
			"url" => "www.cagesworld.co.uk",
			"website_id" => 120,
			"order_prefix" => 'CG',
			"webpark" => '4',
			"server" => array(
					'cagesworld'=>array(
							"webpark" => '4',
							'name'=>'web079',
							'ip'=>array(
									'internal' => '172.20.34.25',
									'external' => '85.92.200.231'
								)
						)
				)
		)
	);
$livesites_array[] = array(
	"Casafina" => array(
			"repo" => "https://bitbucket.org/worldstores/casafina",
			"url" => "www.casafina.com",
			"website_id" => 161,
			"order_prefix" => 'CA',
			"webpark" => '2',
			"server" => array(
					'casafina1'=>array(
							"webpark" => '2',
							'name'=>'web103',
							'ip'=>array(
									'internal' => '172.20.33.27',
									'external' => '85.92.201.175'
								)
						)
				)
		)
	);

$livesites_array[] = array(
	"DoorsWorld" => array(
			"repo" => "https://bitbucket.org/worldstores/skubase",
			"url" => "www.doorsworld.co",
			"website_id" => 123,
			"order_prefix" => '1J',
			"webpark" => '1',
			"server" => array(
					'doorsworld'=>array(
							"webpark" => '1',
							'name'=>'web020',
							'ip'=>array(
									'internal' => '172.20.31.47',
									'external' => '80.79.128.124'
								)
						)
				)
		)
	);

$livesites_array[] = array(
	"GardenFurnitureWorld" => array(
			"repo" => "https://bitbucket.org/worldstores/skubase",
			"url" => "www.gardenfurnitureworld.com",
			"website_id" => 40,
			"order_prefix" => 'GF',
			"webpark" => '3',
			"server" => array(
					'gardenfurnitureworld'=>array(
							"webpark" => '3',
							'name'=>'web068',
							'ip'=>array(
									'internal' => '172.20.33.35',
									'external' => '85.92.201.184'
								)
						)
				)
		)
	);

$livesites_array[] = array(
	"MattressesWorld" => array(
			"repo" => "https://bitbucket.org/worldstores/skubase",
			"url" => "www.mattressesworld.co.uk",
			"website_id" => 28,
			"order_prefix" => 'MW',
			"webpark" => '2',
			"server" => array(
					'mattressesworld'=>array(
							"webpark" => '2',
							'name'=>'web107',
							'ip'=>array(
									'internal' => '172.20.32.39',
									'external' => '85.92.209.209'
								)
						)
				)
		)
	);

$livesites_array[] = array(
	"Modern" => array(
			"repo" => "http://svn.worldstores.co.uk/privatesale/trunk",
			"url" => "www.modern.co.uk",
			"website_id" => 157,
			"order_prefix" => 'MD',
			"webpark" => '3',
			"server" => array(
					'modern'=>array(
							"webpark" => '3',
							'name'=>'web065',
							'ip'=>array(
									'internal' => '172.20.33.30',
									'external' => '85.92.201.172'
								)
						)
				)
		)
	);

$livesites_array[] = array(
	"OfficeSupermarket" => array(
			"repo" => "https://bitbucket.org/worldstores/skubase",
			"url" => "www.officesupermarket.co.uk",
			"website_id" => 11,
			"order_prefix" => 'OS',
			"webpark" => '3',
			"server" => array(
					'officesupermarket'=>array(
							"webpark" => '3',
							'name'=>'web049',
							'ip'=>array(
									'internal' => '172.20.33.25',
									'external' => '85.92.201.166'
								)
						)
				)
		)
	);

$livesites_array[] = array(
	"ShedsWorld" => array(
			"repo" => "https://bitbucket.org/worldstores/skubase",
			"url" => "www.shedsworld.co.uk",
			"website_id" => 43,
			"order_prefix" => 'SW',
			"webpark" => '2',
			"server" => array(
					'shedsworld'=>array(
							"webpark" => '2',
							'name'=>'web034',
							'ip'=>array(
									'internal' => '172.20.32.30',
									'external' => '85.92.209.214'
								)
						)
				)
		)
	);

$livesites_array[] = array(
	"SofasWorld" => array(
			"repo" => "https://bitbucket.org/worldstores/skubase",
			"url" => "www.sofasworld.co.uk",
			"website_id" => 69,
			"order_prefix" => 'SF',
			"server" => array(
					'sofasworld'=>array(
							"webpark" => '3',
							'name'=>'web069',
							'ip'=>array(
									'internal' => '172.20.33.36',
									'external' => '85.92.201.185'
								)
						)
				)
		)
	);











