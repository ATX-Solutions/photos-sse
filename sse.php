<?php

header("Cache-Control: no-cache");
header("Content-Type: text/event-stream");
header("Access-Control-Allow-Origin: *");

$string = file_get_contents('./data.json');
$json = json_decode($string, true);

// error_log(json_encode($json, JSON_PRETTY_PRINT));

$counter = rand(1, 10);

$photos = $json['photos'];

while (true) {
  // Every second, send a "ping" event.

  echo "event: ping\n";
  $curDate = date(DATE_ISO8601);
  echo 'data: {"time": "' . $curDate . '"}';
  echo "\n\n";

  // Send a simple message at random intervals.

  $counter--;

  if (!$counter) {
    $photoIndex = rand(0, count($photos) - 1);
    // echo 'data: ' . json_encode($json[$photoIndex]);
    error_log($photoIndex . json_encode($photos[$photoIndex]));
    echo "event: message\n";
    echo 'data: ' . json_encode(json_encode($photos[$photoIndex])) . "\n\n";

    $counter = rand(1, 10);
  }

  ob_end_flush();
  flush();

  // Break the loop if the client aborted the connection (closed the page)

  if ( connection_aborted() ) break;

  sleep(1);
}
