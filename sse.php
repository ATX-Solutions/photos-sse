<?php

header("Cache-Control: no-cache");
header("Content-Type: text/event-stream");
header("Access-Control-Allow-Origin: *");

$string = file_get_contents('./data.json');
$json = json_decode($string, true);

$photos = $json['photos'];

$errorJSONString  = '{"data": null, "errors": { "message": "Something went wrong", "meta": {} }}';

$errorCounter = 0;

while (true) {
  
  if ($errorCounter == 10) {
    echo "event: error\n";
    echo 'data: ' . json_encode($errorJSONString) . "\n\n";
    $errorCounter = 0;
    sleep(1);
    continue;
  }

  $errorCounter++;
  
  $photoIndex = rand(0, count($photos) - 1);
  error_log($photoIndex . json_encode($photos[$photoIndex]));
  echo "event: message\n";
  echo 'data: ' . json_encode(json_encode($photos[$photoIndex])) . "\n\n";

  ob_end_flush();
  flush();

  if ( connection_aborted() ) break;

  sleep($photoIndex % 2 == 0 ? 1.5 : 1);
}
