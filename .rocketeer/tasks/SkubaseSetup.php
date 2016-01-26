<?php
namespace SkubaseTask;
use Rocketeer\Facades\Rocketeer;
use Rocketeer\Abstracts\AbstractTask;

/**
* SetupSkubase
*
* @uses     AbstractTask
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class SetupSkubase extends AbstractTask
{
	/**
	* Description of the Task
	*
	* @var string
	*/
	protected $description = 'Runs all required tasks for setting up a Skubase site on one off';

	/**
	* Executes the Task
	*
	* @return void
	*/
	public function execute()
	{
		Rocketeer::execute('SkubaseTask\InstallBower');
		Rocketeer::execute('SkubaseTask\InstallApplicationConfig');
	}
}

Rocketeer::add('SkubaseTask\SetupSkubase');