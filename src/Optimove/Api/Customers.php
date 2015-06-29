<?php

namespace oNeDaL\Optimove\Api;


class Customers
{

    private $client;


    public function __construct($client)
    {
        $this->client = $client;
    }


    public function getCustomerActionsByTargetGroup($targetGroupId, $date)
    {
        $result = $this->client->get('customers/GetCustomerActionsByTargetGroup', [
            'targetGroupId' => (int) $targetGroupId,
            'date' => $date
        ]);

        return $result;
    }


    public function getPromoCodesByTargetGroup($targetGroupId, $date)
    {
        $response = $this->client->get('actions/GetPromoCodesByTargetGroup', array(
            'query' => array(
                'targetGroupId' => (int) $targetGroupId,
                'date' => $date
            )
        ));

        return $response->json();

        if ($response->getStatusCode() == 200) {
            return $response->json();
        } else {
            return array();
        }
    }

}