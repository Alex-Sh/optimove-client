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
        $result = $this->client->get('actions/GetAllActions');

        if (isset($result->success) && $result->success === TRUE) {
            return $result->data;
        }

        return FALSE;
    }


    /**
     *
     * @return array
     */
    public function getExecutionChannels()
    {
        $result = [];
        $response = $this->client->get('actions/GetExecutionChannels');
        if (isset($response->success) && $response->success === TRUE) {
            foreach ((array) $response->data as $item) {
                $result[$item->ChannelId] = $item->ChannelName;
            }

            return $result;
        }

        return FALSE;
    }


}