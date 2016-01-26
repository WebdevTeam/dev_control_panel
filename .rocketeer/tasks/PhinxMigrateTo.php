<?php
namespace SkubaseTask;
use Rocketeer\Facades\Rocketeer;
use Rocketeer\Abstracts\AbstractTask;

/**
* PhinxMigrateTo
*
* @uses     AbstractTask
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class PhinxMigrateTo extends AbstractTask
{
    /**
    * Description of the Task
    *
    * @var string
    */
    protected $description = 'Migrates databases for a website.';

    /**
    * Executes the Task
    * Checks if the logs/revision file exists and creates it if not with the appropriate permissions.
    * Increments the revision number if the file exists.
    *
    * @return void
    */
    public function execute()
    {
        $phinx = $this->binary('SkubaseStrategy\PhinxBinary');

        $target = $this->command->ask('What target would you like to migrate to?');

        if (empty($target))
        {
            $this->explainer->error('Error: Target not specified. Aborting Migration.');
            exit();
        }
        elseif ( ! preg_match("/^\d{14}$/",$target))
        {
            $this->explainer->error('Error: Target specified must be in the format yyyymmddhhiiss. Aborting Migration.');
            exit();
        }
        $phinx->setTarget($target);
        $phinx->runForCurrentRelease('migrate');
    }
}

Rocketeer::add('SkubaseTask\PhinxMigrateTo');