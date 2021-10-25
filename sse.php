<?php

header("Cache-Control: no-cache");
header("Content-Type: text/event-stream");
header("Access-Control-Allow-Origin: *");

$string = file_get_contents('./data.json');
$json = json_decode($string, true);

$photos = $json['photos'];

while (true) {
  $photoIndex = rand(0, count($photos) - 1);
  error_log($photoIndex . json_encode($photos[$photoIndex]));
  echo "event: message\n";
  echo 'data: ' . json_encode(json_encode($photos[$photoIndex])) . "\n\n";

  ob_end_flush();
  flush();

  if ( connection_aborted() ) break;

  sleep($photoIndex % 2 == 0 ? 0.5 : 1);
}
