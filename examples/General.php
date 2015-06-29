<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    // first create file 'credentials.txt' (user:pass) in this folder
    $credentials = explode(':', file_get_contents(__DIR__ . '/credentials.txt'));

    $optimove = new oNeDaL\OptimoveClient($credentials[0], $credentials[1]);
    $lastUpdate = $optimove->general->getLastDataUpdate();
    if ($lastUpdate instanceof \DateTime) {
        echo "LastUpdate: " . $lastUpdate->format('Y-m-d') . "\n";
    } else {
        echo "Cannot load last update date, sorry. \n";
        var_dump($lastUpdate);
    }
} catch(\Exception $e) {
    var_dump($e->getMessage());
}

