<?php

$pass = 'password';

function printJson($json) {
    header('Content-Type: application/json');
    echo json_encode($json);
    return;
}

function isJson($string) {
     json_decode($string);
     return (json_last_error() == JSON_ERROR_NONE);
}

if(!isset($_GET['reqUrl']) || !isset($_GET['pass']) || $_GET['pass']!=$pass) {
    $json = ['message' => 'Error with the request'];
    return printJson($json);
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $_GET['reqUrl']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$headers = array();
$headers[] = "Dnt: 1";
$headers[] = "Accept-Encoding: gzip, deflate, sdch";
$headers[] = "Accept-Language: en";
$headers[] = "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36";
$headers[] = "Accept: */*";

$referer = null;
if(isset($_GET['referer'])) {
	$referer = $_GET['referer'];	
}

if (!is_null($referer)) {
    $headers[] = "Referer: " . $referer;
    $headers[] = "Origin: " . $referer;
}
$headers[] = "Connection: keep-alive";

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$json = curl_exec($ch);

if (curl_errno($ch)) {
    $json = ['message' => 'Error with the request', 'error' => curl_errno($ch)];
    return printJson($json);
}
if(isJson($json)) {
    header('Content-Type: application/json');    
}
echo $json;
?>