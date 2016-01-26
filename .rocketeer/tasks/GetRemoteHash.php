<?php
namespace SkubaseTask;
use Rocketeer\Facades\Rocketeer;
use Rocketeer\Abstracts\AbstractTask;

/**
* GetRemoteHash
* Custom task to fetch the shortened hash the current release is on.
*
* @uses     AbstractTask
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class GetRemoteHash extends AbstractTask
{
    /**
    * Description of the Task
    *
    * @var string
    */
    protected $description = 'Fetches the shortened hash the current release is on.';

    /**
    * Executes the Task
    * Checks if the logs/revision file exists and creates it if not with the appropriate permissions.
    * Increments the revision number if the file exists.
    *
    * @return void
    */
    public function execute()
    {
        return $this->scm->runForCurrentRelease('log', ['--pretty=format:\'%h\'','-n 1']);
    }
}

Rocketeer::add('SkubaseTask\GetRemoteHash');