<?php

namespace oNeDaL;

use oNeDaL\Optimove\Api;


if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    throw new Exception('Optimove client needs PHP 5.4.0 or newer.');
}


class OptimoveClient
{
    const API_BASE_URL = 'https://api.optimove.net';

    const DEFAULT_API_VERSION = 'v1.6';

    const API_DEFAULT_CONTENT_TYPE = 'application/JSON';

    const DEFAULT_USER_AGENT = 'Optimove API PHP client v1';


    /** @var GuzzleHttp\Client HTTP client*/
    private $client;

    public $general;
    public $actions;
    public $customers;
    public $groups;


    public function __construct($username, $password)
    {
        $guzzleConfig = [
            'base_uri' => self::API_BASE_URL . '/' . self::DEFAULT_API_VERSION . '/',
            'verify' => FALSE,
            'timeout' => 15,
            'headers' => array(
                'Accept'       => self::API_DEFAULT_CONTENT_TYPE,
                'User-Agent'   => self::DEFAULT_USER_AGENT,
                'Content-type' => self::API_DEFAULT_CONTENT_TYPE
            ),
            'allow_redirects' => [
                'max'       => 5,
                'strict'    => TRUE,
                'referer'   => TRUE,
                'protocols' => ['https']
            ]
        ];


        $this->client = new \oNeDaL\Optimove\Api\HttpClient($guzzleConfig, $username, $password, function($u, $p) {
            return $this->general->login($u, $p);
        });

        $this->general = new Api\General($this->client);
        $firstToken = $this->general->login($username, $password);
        if ($firstToken === FALSE) {
            throw new \Exception('Cannot login to Optimove API due problem with API token.');
        } else {
            $this->client->setToken($firstToken);
        }

        $this->customers = new Api\Customers($this->client);
    }


    // @todo Remove, for quick testing only.
    public function debugSetToken($injectedToken)
    {
        echo "Injecting testing token \n";
        $this->client->setToken($injectedToken);
    }

}
