<?php

namespace oNeDaL\Optimove\Api;
use oNeDaL\Optimove\Api;

class Actions
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }


    public function getExecutedCampaignDetails($date)
    {
        $result = $this->client->get('actions/GetExecutedCampaignDetails', [
                'date' => $date
        ]);

        return $result;

    }


}