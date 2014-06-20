<?php
set_time_limit(0);
date_default_timezone_set('Europe/London');

if(isset($argv[2]) && isset($argv[3]) && isset($argv[4]))
{
    $mode = $argv[2];
    $site_switcher = $argv[3];
    $domain_name = $argv[4];

    $dev_control_data_file = "/Users/usmanniazi/Sites/dev_control_panel/logs/control_panel_feed_webdev.log";
    $json_data = json_decode(file_get_contents($dev_control_data_file));

    if(!isset($json_data->local) || !isset($json_data->webdev))
    {
        switch(json_last_error())
        {
            case JSON_ERROR_DEPTH:
                echo ' - Maximum stack depth exceeded';
            break;
            case JSON_ERROR_CTRL_CHAR:
                echo ' - Unexpected control character found';
            break;
            case JSON_ERROR_SYNTAX:
                echo ' - Syntax error, malformed JSON';
            break;
            case JSON_ERROR_STATE_MISMATCH:
                echo ' - Invalid or malformed JSON';
            break;
            case JSON_ERROR_UTF8:
                echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
        }
        exit();
    }

    if($site_switcher == "l")
    {

        if(isset($argv[5])){$branch_name = $argv[5];}
        if(isset($argv[6])){$database_name = $argv[6];}
        if(isset($argv[7])){$port_number = $argv[7];}

        if(isset($json_data->local))
        {
            $local_sites = $json_data->local;

            if(!is_array($local_sites))
            {
                $local_sites = array();
            }

            $current_domains = array();
            $current_branches = array();
            foreach ($local_sites as $site)
            {
                $current_domains[] = $site->domain;
                $current_branches[] = $site->branch;
            }
            
            if(isset($domain_name) && $domain_name != "-")
            {
                $needle = $domain_name;
                $needle_name = "domain";
                $haystack = $current_domains;
            }
            elseif(isset($branch_name) && $branch_name != "-")
            {
                $needle = $branch_name;
                $needle_name = "branch";
                $haystack = $current_branches;
            }

            if($mode == "ADD")
            {
                if(!in_array($needle, $haystack))
                {
                    $local_sites[] = array("port" => $port_number, "domain" => $domain_name, "db" => $database_name, "branch" => $branch_name);
                    $json_data->local = $local_sites;                
                    file_put_contents($dev_control_data_file, json_encode($json_data));
                    echo "1";
                }
                else
                {
                    echo "2";
                }
            }
            elseif($mode == "DEL")
            {
                if(in_array($needle, $haystack))
                {
                    $newlocal_sites = array();
                    foreach ($local_sites as $site)
                    {
                        if($site->$needle_name != $needle)
                        {
                            $newlocal_sites[] = $site;
                        }
                    }                
                    $json_data->local = $newlocal_sites;                
                    file_put_contents($dev_control_data_file, json_encode($json_data));
                    echo "1";
                }
                else
                {
                    echo "2";
                }
            }
            else
            {
                echo "3";
            }
        }
    }
    elseif($site_switcher == "w")
    {
        if(isset($argv[5])){$branch_name = $argv[5];}
        if(isset($argv[6])){$revision = $argv[6];}

        if(isset($json_data->webdev))
        {
            $webdev_sites = $json_data->webdev;

            

            if($mode == "ADD")
            {
                $update = FALSE;

                    foreach ($webdev_sites as $w_site)
                    {
                        if($w_site->testsite == $domain_name)
                        {
                            $w_site->testsite = $domain_name;
                            $w_site->branch = $branch_name;
                            $w_site->revision = $revision;
                            $w_site->last_updated = strtotime('NOW');
                            $update=TRUE;
                        }

                        $newwebdev_sites[] = $w_site;
                    }

                    if($update==FALSE)
                    {
                        $new_site = new stdClass;
                        $new_site->testsite = $domain_name;
                        $new_site->branch = $branch_name;
                        $new_site->revision = $revision;
                        $new_site->last_updated = strtotime('NOW');
                        $newwebdev_sites[] = $new_site;
                    }

                    $json_data->webdev = $newwebdev_sites;
                    file_put_contents($dev_control_data_file, json_encode($json_data));
                    echo "1";
            }
            else
            {
                echo "3";
            }
        }
    }
    elseif($site_switcher == "s")
    {
        if(isset($argv[5])){$branch_name = $argv[5];}
        if(isset($argv[6])){$revision = $argv[6];}

        if(isset($json_data->skusites))
        {
            $skusites_sites = $json_data->skusites;

            

            if($mode == "ADD")
            {
                $update = FALSE;

                    foreach ($skusites_sites as $s_site)
                    {
                        if($s_site->testsite == $domain_name)
                        {
                            $s_site->testsite = $domain_name;
                            $s_site->branch = $branch_name;
                            $s_site->revision = $revision;
                            $s_site->last_updated = strtotime('NOW');
                            $update=TRUE;
                        }

                        $newskusites_sites[] = $s_site;
                    }

                    if($update==FALSE)
                    {
                        $new_site = new stdClass;
                        $new_site->testsite = $domain_name;
                        $new_site->branch = $branch_name;
                        $new_site->revision = $revision;
                        $new_site->last_updated = strtotime('NOW');
                        $newskusites_sites[] = $new_site;
                    }

                    $json_data->skusites = $newskusites_sites;
                    file_put_contents($dev_control_data_file, json_encode($json_data));
                    echo "1";
            }
            else
            {
                echo "3";
            }
        }
    }
}


?>