<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    // first create file 'credentials.txt' (user:pass) in this folder
    $credentials = explode(':', file_get_contents(__DIR__ . '/credentials.txt'));

    $optimove = new oNeDaL\OptimoveClient($credentials[0], $credentials[1]);
    $customerActions = $optimove->customers->getCustomerActionsByTargetGroup(122, '2015-09-15');
    $cnt = count($customerActions);
    echo "Customer actions by target group count: {$cnt} \n";
} catch(\Exception $e) {
    var_dump($e->getMessage());
}

