<?php
namespace SkubaseTask;
use Rocketeer\Facades\Rocketeer;
use Rocketeer\Abstracts\AbstractTask;

/**
* InstallApplicationConfig
* Installs Application Config File for a website
* @uses     AbstractTask
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class InstallApplicationConfig extends AbstractTask
{
	/**
	* Description of the Task
	*
	* @var string
	*/
	protected $description = 'Installs Application Config File for a website';

	/**
	* Executes the Task
	*
	* @return void
	*/
	public function execute()
	{
		if ($this->isSetup())
		{
	        $config_path = $this->paths->getHomeFolder() . '/shared/config';

	        if ( ! $this->fileExists("{$config_path}/application.config.php"))
	        {
				$this->explainer->line('Installing Application Config');
				$this->runForCurrentRelease('cp -a config/application.config.example.php config/application.config.php');
				$this->share('config/application.config.php');
	        }
		}
		else
		{
			$this->explainer->error('Task Completed with Errors: Current directory does not exist on remote server and is required before this task can run.');
		}

		//This task could then replace the db info with actual values for the application

	}
}

Rocketeer::add('SkubaseTask\InstallApplicationConfig');