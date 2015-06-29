<?php

namespace oNeDaL\Optimove\Api;
use oNeDaL\Optimove\Api;

class Actions
{

    public function getExecutedCampaignDetails($date)
    {
        $response = $this->client->get('actions/GetExecutedCampaignDetails', array(
            'headers' => array('Authorization-Token' => $this->token),
            'query' => array('date' => $date)
        ));

        if ($response->getStatusCode()) {
            return $response->json();
        } else {
            return array();
        }
    }


}