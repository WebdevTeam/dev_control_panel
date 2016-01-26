<?php
namespace SkubaseTask;
use Rocketeer\Facades\Rocketeer;
use Rocketeer\Abstracts\AbstractTask;

/**
* GetLocalUser
* Custom Task to fetch the current local user.
*
* @uses     AbstractTask
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class GetLocalUser extends AbstractTask
{
    /**
    * Description of the Task
    *
    * @var string
    */
    protected $description = 'Fetches the current bash user name.';

    protected $local = true;

    /**
    * Executes the Task
    * Checks if the logs/revision file exists and creates it if not with the appropriate permissions.
    * Increments the revision number if the file exists.
    *
    * @return void
    */
    public function execute()
    {
        $user = $this->run('echo $USER');

        if ($user == 'user01')
            $user = 'Potter';

        return $user;
    }
}

Rocketeer::add('SkubaseTask\GetLocalUser');