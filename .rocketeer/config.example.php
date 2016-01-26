<?php
use Rocketeer\Services\Connections\ConnectionsHandler;

return array(

	// The name of the application to deploy
	// This will create a folder of the same name in the root directory
	// configured above, so be careful about the characters used
	'application_name' => 'skubase',

	// Plugins
	////////////////////////////////////////////////////////////////////

	// The plugins to load
	'plugins'          => array(
		// 'Rocketeer\Plugins\Slack\RocketeerSlack',
	),

	// Logging
	////////////////////////////////////////////////////////////////////

	// The schema to use to name log files
	'logs'             => function (ConnectionsHandler $connections) {
		return sprintf('%s-%s-%s.log', $connections->getConnection(), $connections->getStage(), date('Ymd'));
	},

	// Remote access
	//
	// You can either use a single connection or an array of connections
	////////////////////////////////////////////////////////////////////

	// The default remote connection(s) to execute tasks on
	// 'default'          => array('tst001worldstores','tst002worldstores'),
	'default'          => array('tst001worldstores'),

	// The various connections you defined
	// You can leave all of this empty or remove it entirely if you don't want
	// to track files with credentials : Rocketeer will prompt you for your credentials
	// and store them locally
	'connections'      => array(
		'tst001worldstores' => array(
			'host'          => 'tst001.worldstores.co.uk',
			'username'      => 'deploy',
			'password'      => 'password',
			'key'           => '',
			'keyphrase'     => '',
			'agent'         => '',
			'db_role'       => true,
		),
		'tst002sofasworld' => array(
			'host'          => 'tst002.worldstores.co.uk',
			'username'      => 'deploy',
			'password'      => 'password',
			'key'           => '',
			'keyphrase'     => '',
			'agent'         => '',
			'db_role'       => true,
		),
	),

	/*
	 * In most multiserver scenarios, migrations must be run in an exclusive server.
	 * In the event of not having a separate database server (in which case it can
	 * be handled through connections), you can assign a 'db_role' => true to the
	 * server's configuration and it will only run the migrations in that specific
	 * server at the time of deployment.
	 */
	'use_roles'        => false,

	// Contextual options
	//
	// In this section you can fine-tune the above configuration according
	// to the stage or connection currently in use.
	// Per example :
	// 'stages' => array(
	// 	'staging' => array(
	// 		'scm' => array('branch' => 'staging'),
	// 	),
	//  'production' => array(
	//    'scm' => array('branch' => 'master'),
	//  ),
	// ),
	////////////////////////////////////////////////////////////////////

	'on'               => array(

		// Stages configurations
		'stages'      => array(),
		// Connections configuration
		'connections' => array(
			'tst001worldstores' => array(
				'remote' => array(
					'app_directory' => 'worldstores'
					),
				'newrelic' => array(
					'app_name' => 'WorldStores',
					'application_id' => '5733365',
					),
				),
			'tst002sofasworld' => array(
				'remote' => array(
					'app_directory' => 'sofasworld'
					),
				),
			),

	),

	// 'deployed_by'	=> 'Dave Ward',
	// 'repository_name'	=> 'skubase',
	// 'newrelic_api_key'	=> 'newrelic_api_key',
	// 'slack'	=> array(
	// 	'channel' => '#channel-slack',
	// 	'webhook' => 'slack_hook_url_from_incoming_webhook',
	// 	),

);
