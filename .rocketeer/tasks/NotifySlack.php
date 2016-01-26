<?php
namespace SkubaseTask;
use Rocketeer\Facades\Rocketeer;
use Rocketeer\Abstracts\AbstractTask;

/**
* NotifySlack
*
* @uses     AbstractTask
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class NotifySlack extends AbstractTask
{
    /**
    * Description of the Task
    *
    * @var string
    */
    protected $description = 'Submits a message to a slack channel';

    /**
    * Executes the Task
    * Checks if the logs/revision file exists and creates it if not with the appropriate permissions.
    * Increments the revision number if the file exists.
    *
    * @return void
    */
    public function execute()
    {
        $slack_config = $this->rocketeer->getOption('slack');
        $message = $this->getMessage();

        if ( ! empty($message) && isset($slack_config['webhook']))
        {
            $data = array(
                "channel" => isset($slack_config['channel']) ? $slack_config['channel'] : '#general',
                "username" => 'rocketeer',
                "text" => $message,
                "emoji" => "rocket",
                );

                $payload = array(
                    "payload" => json_encode($data)
                    );

                $request = new \RestRequest("https://hooks.slack.com/services/{$slack_config['webhook']}", 'POST', $payload);
                $request->execute();

                $this->explainer->line("Updated Slack Deployments API");
        }
    }

    protected function getMessage()
    {
        $message = '';
        $local_user = $this->rocketeer->getOption('deployed_by');
        if (empty($local_user))
        {
            $local_user = $this->executeTask('SkubaseTask\GetLocalUser');
        }
        $branch     = $this->connections->getRepositoryBranch();
        $stage      = $this->connections->getStage();
        $connection = $this->connections->getConnection();
        $server     = $this->connections->getServer();
        $repository_name = $this->rocketeer->getOption('repository_name');

        if ($stage)
        {
            $connection = $stage.'@'.$connection;
        }

        $server_string = '';
        if ($server != 0)
        {
            $server_string = " ({$server})";
        }

        //Split event by the period.
        $event_parts = explode(".", $this->event);

        if (count($event_parts) == 2)
        {
            $task = $event_parts[0];
            $event = $event_parts[1];

            switch ($event)
            {
                case 'before':
                    $message = "_*{$repository_name}:* {$task} fired by {$local_user} for branch '{$branch}' on '{$connection}'{$server_string}_";
                    break;

                case 'after':
                    $remote_tag = $this->executeTask('SkubaseTask\GetRemoteTag');
                    $message = "> *{$repository_name}:* Completed {$task} for branch '{$branch}' on '{$connection}'{$server_string} (User: {$local_user}). _Now at *{$remote_tag}*_.";
                    break;
            }

        }

        return $message;

    }
}

Rocketeer::add('SkubaseTask\NotifySlack');