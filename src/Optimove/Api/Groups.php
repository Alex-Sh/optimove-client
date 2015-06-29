<?php

namespace oNeDaL\Optimove\Api;
use oNeDaL\Optimove\Api;

class Groups
{

    public function getTargetGroupsByDate($date)
    {
        $response = $this->client->get('groups/GetTargetGroupsByDate', array(
            'query' => array('date' => $date)
        ));

        if ($response->getStatusCode()) {
            return $response->json();
        } else {
            return array();
        }
    }

}