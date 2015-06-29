<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $date = '2015-05-05';

    // first create file 'credentials.txt' (user:pass) in this folder
    $credentials = explode(':', file_get_contents(__DIR__ . '/credentials.txt'));

    $optimove = new oNeDaL\OptimoveClient($credentials[0], $credentials[1]);
    $executedCampaignDetails = $optimove->actions->getExecutedCampaignDetails($date);
    echo "Executed campaign details for date {$date}: \n";
    var_dump($executedCampaignDetails);
} catch(\Exception $e) {
    var_dump($e->getMessage());
}

