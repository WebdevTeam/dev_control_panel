<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$client = "21860306";
$pass = 'H6eDZY2rLWvghzcr';
$reference = '3600101924886300';
$vpi_url = 'https://mars.transaction.datacash.com/Transaction';

$vtid = "";

if (isset($_GET['ref']))
        $reference = $_GET['ref'];
if (isset($_GET['vtid']))
        $vtid = $_GET['vtid'];

if($vtid=="21860568"){
    $client = "21860568";
    $pass='mCIYmCIYZY2rLWvg';
}
else if($vtid==21860952){
    $client = '21860952';
    $pass = 'bNtYk15hj8891Al3';
}else 
if($vtid=="99005522"){
    $client = "99005522";
    $pass='W6yJqCaCA';
    $vpi_url = 'https://testserver.datacash.com/Transaction';
}

if ($reference != '' && $reference != 0) {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
        $xml .= "<Request>\n";
        $xml .= "\t<Authentication>\n";
        $xml .= "\t\t<password>{$pass}</password>\n";
        $xml .= "\t\t<client>{$client}</client>\n";
        $xml .= "\t</Authentication>\n";
        $xml .= "\t<Transaction>\n";
        $xml .= "\t\t<HistoricTxn>\n";
        $xml .= "\t\t\t<method>query</method>\n";
        $xml .= "\t\t\t<reference>" . $reference . "</reference>\n";
        $xml .= "\t\t</HistoricTxn>\n";
        $xml .= "\t</Transaction>\n";
        $xml .= "</Request>\n";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $vpi_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $hps_resp = curl_exec($ch);
        $errno = curl_errno($ch);
        $err_str = curl_error($ch);
        $curl_getinfo = curl_getinfo($ch);
        echo "<xmp>$xml \n\n $hps_resp";
        echo "\n\n\n$errno\n$err_str\n\n$curl_getinfo";
        print_r($curl_getinfo);
        echo "</xmp>";
} else {
        echo "Invalid Ref: $reference";
}
?>
