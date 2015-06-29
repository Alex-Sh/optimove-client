<?php

namespace oNeDaL\Optimove\Api;
use oNeDaL;

class General
{
    private $client;


    public function __construct($httpClient)
    {
        $this->client = $httpClient;
    }


    public function login($username, $password)
    {
        $result = $this->client->get("general/login?username={$username}&password={$password}");
        if ($result->success) {
            if (\isValidGuid(strtolower($result->data))) {
                return $result->data;
            }
        }

        return FALSE;
    }


    /**
     * @return \DateTime|FALSE Last update date, otherwise FALSE
     */
    public function getLastDataUpdate()
    {
        $result = NULL;

        $result = $this->client->get('general/GetLastDataUpdate');
        if ($result->success && isset($result->data->Date)) {
            $dt = new \DateTime($result->data->Date);
            return $dt;
        }

        return FALSE;
    }

}