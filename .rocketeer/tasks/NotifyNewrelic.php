<?php
namespace SkubaseTask;
use Rocketeer\Facades\Rocketeer;
use Rocketeer\Abstracts\AbstractTask;

/**
* NotifyNewrelic
*
* @uses     AbstractTask
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class NotifyNewrelic extends AbstractTask
{
    /**
    * Description of the Task
    *
    * @var string
    */
    protected $description = 'Submits a deployment release to NewRelic for a deployment.';

    /**
    * Executes the Task
    * Checks if the logs/revision file exists and creates it if not with the appropriate permissions.
    * Increments the revision number if the file exists.
    *
    * @return void
    */
    public function execute()
    {
        //Check to see if a NewRelic config is in place
        $newrelic_site_config = $this->rocketeer->getOption('newrelic');
        if ($newrelic_site_config != NULL)
        {
            $payload = array(
                "deployment" => array(
                    "app_name" => $newrelic_site_config['app_name'],
                    "application_id" => $newrelic_site_config['application_id'],
                    "description" => Rocketeer::execute('SkubaseTask\GetRemoteTag'),
                    "revision" => Rocketeer::execute('SkubaseTask\GetRemoteHash'),
                    "user" => Rocketeer::execute('SkubaseTask\GetLocalUser'),
                    ),
                );

            $request = new \RestRequest('https://api.newrelic.com/deployments.xml', 'POST', $payload);
            $request->setCustomHeader("x-api-key:{$this->rocketeer->getOption('newrelic_api_key')}");
            $request->execute();
            $this->explainer->line("Updated NewRelic Deployments API for {$newrelic_site_config['app_name']}");
        }
    }
}

Rocketeer::add('SkubaseTask\NotifyNewrelic');