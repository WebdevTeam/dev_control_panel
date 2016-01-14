<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once './includes/common.php';

$svn_url = 'http://svn.worldstores.co.uk/skubase/branches/';//$_GET['svn_url'];
echo 'svn list '.$svn_url;
$a = exec('/usr/local/bin/svn list http://svn.worldstores.co.uk/skubase/branches/',$output);

