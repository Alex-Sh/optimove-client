<?php

namespace oNeDaL\Optimove\Api;


class Customers
{

    private $client;


    public function __construct($client)
    {
        $this->client = $client;
    }


    public function getCustomerActionsByTargetGroup($targetGroupId, $date, $pageSize = 10000)
    {
        $result = [];
        $fetched = 0;
        $skip = 0;

        do {
            $response = $this->client->get('customers/GetCustomerActionsByTargetGroup', [
                'targetGroupId' => (int) $targetGroupId,
                'date'  => $date,
                '$top'  => $pageSize,
                '$skip' => $skip
            ]);

            if ($response->success) {
                if (is_array($response->data)) {
                    $fetched = count($response->data);
                    if ($fetched > 0) {
                        $result = array_merge($result, $response->data);
                        $skip += $fetched;
                    }
                }
            }

        } while ($fetched > 0);

        return $result;
    }


    public function getPromoCodesByTargetGroup($targetGroupId, $date, $pageSize = 10000)
    {
        $result = [];
        $fetched = 0;
        $skip = 0;

        do {
            $response = $this->client->get('actions/GetPromoCodesByTargetGroup', [
                'targetGroupId' => (int) $targetGroupId,
                'date'  => $date,
                '$top'  => $pageSize,
                '$skip' => $skip
            ]);

            if ($response->success) {
                if (is_array($response->data)) {
                    $fetched = count($response->data);
                    if ($fetched > 0) {
                        $result = array_merge($result, $response->data);
                        $skip += $fetched;
                    }
                }
            }

        } while ($fetched > 0);

        return $result;
    }

}