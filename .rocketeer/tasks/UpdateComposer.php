<?php
namespace SkubaseTask;
use Rocketeer\Facades\Rocketeer;
use Rocketeer\Abstracts\AbstractTask;

/**
* UpdateComposer
*
* @uses     AbstractTask
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class UpdateComposer extends AbstractTask
{
    /**
    * Description of the Task
    *
    * @var string
    */
    protected $description = 'Updates Composer for a website.';

    /**
    * Executes the Task
    * Checks if the logs/revision file exists and creates it if not with the appropriate permissions.
    * Increments the revision number if the file exists.
    *
    * @return void
    */
    public function execute()
    {
        $target = $this->command->ask("Are you SURE you want to do a composer update???\n------------------------------------------------\nThis will perform the command 'composer update -v --no-interaction --no-dev --prefer-dist' on the remote server(s) and IGNORE AND OVERWRITE THE COMPOSER LOCK FILE.\nPlease make sure you know what you are doing by this!\n=====\n(y/n)\n=====\n");

        if ($target === 'y')
        {
            $this->runForCurrentRelease('composer update -v --no-interaction --no-dev --prefer-dist');
        }
    }
}

Rocketeer::add('SkubaseTask\UpdateComposer');