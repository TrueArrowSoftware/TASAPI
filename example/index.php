<?php

require_once dirname(__FILE__).'/configure.php';

echo 'This sample file is to call the API request and show data from API. ';
echo "\r\n This is API Client script. ";

$param = array(
    'type' => 'info',
    'parameters' => array(),
    'options' => array(
        'limit' => 100,
        'orderby' => 'id',
        'order' => 'asc',
        'page' => 1,
    ),
    'authentication' => array(
        'username' => 'demo',
        'authkey' => 'demo',
    ),
);
echo $GLOBALS['AppConfig']['HomeURL'];
echo "\r\n<br /><pre>\r\n\r\n";

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $GLOBALS['AppConfig']['HomeURL'].'/api/');
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($param));
curl_setopt($handle, CURLOPT_POST, 1);
//echo "<pre>";
$response = curl_exec($handle);
//echo "Sending ".  json_encode($param );
header('content-type: application/json');
echo $response;

echo "\r\n<br />\r\n Next Call \r\n\r\n<br />\r\n";

$param = array(
    'type' => 'SomeProcess',
    'parameters' => array(),
    'options' => array(
        'limit' => 100,
        'orderby' => 'id',
        'order' => 'asc',
        'page' => 1,
    ),
    'authentication' => array(
        'username' => 'demo',
        'authkey' => 'demo',
    ),
);

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $GLOBALS['AppConfig']['HomeURL'].'/api/');
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($param));
curl_setopt($handle, CURLOPT_POST, 1);
//echo "<pre>";
$response = curl_exec($handle);
echo $response;
