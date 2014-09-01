<?php
require_once './includes/common.php';

$new_project = $path_bash_scripts . '.newproject.sh';

$domain = $_POST['domain'];
$repository = $_POST['repository'];
$branch = $_POST['branch'];
$ticket_number = $_POST['ticket_number'];
$database = $_POST['database'];
$host = $_POST['host'];
$commit_message = $_POST['commit_message'];
//echo $cmd = "sudo bash $new_project -d $domain -b $branch -t $ticket_number   -s $database -o $host -r $repository";
//Using MacScript, open new terminal and tell it to run .endproject.sh with appropiat params
echo exec('osascript -e \'tell application "Terminal" to do script "sudo bash '.$new_project.' -d '.$domain.' -b '.$branch.' -t '.$ticket_number.' -m \"'.$commit_message.'\"  -s '.$database.' -o '.$host.' -r '.$repository.'"\'');