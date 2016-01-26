<?php
namespace SkubaseTask;
use Rocketeer\Facades\Rocketeer;
use Rocketeer\Abstracts\AbstractTask;

/**
* PhinxRollbackAll
*
* @uses     AbstractTask
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class PhinxRollbackAll extends AbstractTask
{
    /**
    * Description of the Task
    *
    * @var string
    */
    protected $description = 'Rollsback all database migrations for a website.';

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
        $phinx->runForCurrentRelease('rollbackAll');
    }
}

Rocketeer::add('SkubaseTask\PhinxRollbackAll');