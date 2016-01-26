<?php
namespace SkubaseTask;
use Rocketeer\Facades\Rocketeer;
use Rocketeer\Abstracts\AbstractTask;

/**
* InstallBower
*
* @uses     AbstractTask
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class InstallBower extends AbstractTask
{
	/**
	* Description of the Task
	*
	* @var string
	*/
	protected $description = 'Installs Bower for a website from bower.json';

	/**
	* Executes the Task
	*
	* @todo  Detect if components directory exists already and if so archive it with a yyyymmddhhiiss timestampinto archive/components.
	* @todo  Remove --allow-root option as this will be run by the deploy user
	* @return void
	*/
	public function execute()
	{
		if ($this->isSetup())
		{
			$this->explainer->line('Installing bower');
			$this->runForCurrentRelease('bower install --allow-root --production --force-latest');
			$this->share('components');
		}
		else
		{
			$this->explainer->error('Task Completed with Errors: The \'current\' directory does not exist on remote server and is required before this task can run.');
		}

	}
}

Rocketeer::add('SkubaseTask\InstallBower');