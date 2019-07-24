<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "starting...\n ";
echo "and second line ";


date_default_timezone_set('Australia/Sydney');

echo 'Date: ' . date("D M d, Y G:i a") . '<br>';

$secret_key = "your private key";
$secret_key_encoded = base64_decode($secret_key);

$public_key = "your public key";

$milliseconds = round(microtime(true) * 1000);

$msg = "/account/balance\n" . $milliseconds . "\n";
echo "Message is:\n" . $msg;

$encodedMsg =   hash_hmac('sha512', $msg, $secret_key_encoded, true);
$base64Msg = base64_encode($encodedMsg);
echo "Encoded Message is: \n" . $base64Msg . "<br>";

// Create a stream
$opts = array(
  'http'=>array(
        'method'=>"GET",
        'header'=>      "Accept: */*\r\n" .
                        "Accept-Charset: UTF-8\r\n" .
                        "Content-Type: application/json\r\n" .
                        "apikey: " . $public_key . "\r\n" .
                        "timestamp: " . $milliseconds . "\r\n" .
                        "User-Agent: btc markets php client\r\n" .
                        "signature: " . $base64Msg . "\r\n"
  )
);

$context = stream_context_create($opts);

var_dump($opts);

// Open the file using the HTTP headers set above
$json = file_get_contents('https://api.btcmarkets.net/account/balance', false, $context);

var_dump($json);


?>
