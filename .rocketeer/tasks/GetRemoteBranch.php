<?php
namespace SkubaseTask;
use Rocketeer\Facades\Rocketeer;
use Rocketeer\Abstracts\AbstractTask;

/**
* GetRemoteBranch
* Custom task to get the name of the branch the current release is on.
*
* @uses     AbstractTask
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class GetRemoteBranch extends AbstractTask
{
    /**
    * Description of the Task
    *
    * @var string
    */
    protected $description = 'Fetches the name of the branch the current release is on.';

    /**
    * Executes the Task
    * Checks if the logs/revision file exists and creates it if not with the appropriate permissions.
    * Increments the revision number if the file exists.
    *
    * @return void
    */
    public function execute()
    {
        return $this->scm->runForCurrentRelease('rev-parse', ['--abbrev-ref','HEAD']);
    }
}

Rocketeer::add('SkubaseTask\GetRemoteBranch');