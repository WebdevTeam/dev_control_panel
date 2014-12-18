<?php
require_once './includes/common.php';

$new_project = $path_bash_scripts . '/.newproject.sh';



$fields = json_decode($_POST['data']);

$domain = $fields->domain->value;
$repository = $fields->repository->value;
$branch = $fields->branch_input->value;
$ticket_number = $fields->ticket_number->value;
$database = $fields->database->value;
$host = $fields->host->value;
$commit_message = $fields->commit_message->value;

//Using MacScript, open new terminal and tell it to run .new_project.sh with appropiate params
$status = exec('osascript -e \'tell application "Terminal" to do script "sudo bash '.$new_project.' -d '.$domain.' -b '.$branch.' -t '.$ticket_number.' -m \"'.$commit_message.'\"  -s '.$database.' -o '.$host.' -r '.$repository.'"\'');
$cmd = "sudo bash {$new_project} -d {$domain} -b {$branch} -t {$ticket_number} -m {$commit_message}  -s {$database} -o {$host} -r {$repository}";

echo json_encode(array("status" => $status,
					   "command" =>$cmd));