<?php

// Pfad zur config.json Datei
$configFilePath = 'config.json';

// Lese die Datei ein
$configData = file_get_contents( $configFilePath );

// Dekodiere die JSON-Daten
$config = json_decode( $configData, true );

$uuid = $config[ "apikey" ];

if( !isset( $_REQUEST[ "apiKey" ] ) || $_REQUEST[ "apiKey" ] != $uuid ) {

    http_response_code( 403 );
    exit();

}


// URL des RSS-Feeds
$rss_url = 'https://www.tagesschau.de/infoservices/alle-meldungen-100~rss2.xml';

// RSS-Feed laden
$rss = simplexml_load_file($rss_url);

// Meldungen in ein Array speichern
$items = [];
foreach ($rss->channel->item as $item) {
    $items[] = [
        'title' => (string) $item->title,
        'link' => (string) $item->link,
        'pubDate' => strtotime((string) $item->pubDate),
        'description' => $item->description
    ];
}

// Meldungen nach Datum absteigend sortieren
usort($items, function($a, $b) {
    return $b['pubDate'] - $a['pubDate'];
});

// Die letzten drei Meldungen ausgeben

$obj = array( "title" => "", "body" => "" );

$latest_items = array_slice($items, 0, 3);
$i = 1;
$str = "";
$tmp = array();
foreach ($latest_items as $item) {

    $dt = new DateTime();
    $dt->setTimezone(new DateTimeZone('Europe/Berlin'));
    $dt->setTimestamp( $item[ "pubDate" ] );
    $tmp[] = "**" .$item[ "title" ] ."**<br><span style='font-size: 2em;'>" .$item[ "description" ] . "</span> (" .$dt->format( 'H:i' ) .")";
    $i++;

}

$obj[ "body" ] = implode( "<br><br>", $tmp );
echo json_encode( $obj );
?>

