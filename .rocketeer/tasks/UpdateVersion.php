<?php
namespace SkubaseTask;
use Rocketeer\Facades\Rocketeer;
use Rocketeer\Abstracts\AbstractTask;

/**
* UpdateVersion
* Updates the CSS version number (held in va_global_settings) for a website.
* @uses     AbstractTask
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class UpdateVersion extends AbstractTask
{
    /**
    * Description of the Task
    *
    * @var string
    */
    protected $description = 'Updates the CSS version number (held in va_global_settings) for a website.';

    /**
    * Executes the Task
    * Checks if the logs/version file exists and creates it if not with the appropriate permissions.
    * Increments the version number if the file exists.
    *
    * @return void
    */
    public function execute()
    {
        if ($this->fileExists($this->releasesManager->getCurrentReleasePath()."/cli/update_version.php"))
        {
            $this->runForCurrentRelease(array('cd cli','php update_version.php'));
            $this->explainer->line('Incremented CSS version in `va_global_settings` on remote server');
        }
        else
        {
            $this->explainer->error('CLI Script for updating the CSS version not found.');
        }
    }
}

Rocketeer::add('SkubaseTask\UpdateVersion');