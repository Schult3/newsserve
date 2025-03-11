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


$obj = array( "title" => "Test", "body" => "Das ist ein Test" );

echo json_encode( $obj );
?>

