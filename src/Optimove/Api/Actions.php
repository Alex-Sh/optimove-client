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

        if (isset($result->success) && $result->success === TRUE) {
            return $result->data;
        }

        return FALSE;
    }


    public function getAllActions()
    {
        $result = $this->client->get('actions/GetAllActions', [
            'date' => $date
        ]);

        if (isset($result->success) && $result->success === TRUE) {
            return $result->data;
        }

        return FALSE;
    }


}