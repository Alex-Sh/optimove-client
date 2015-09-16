<?php

namespace oNeDaL\Optimove\Api;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr7\Http\Message\RequestInterface;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Client;

class HttpClient
{
    private $client;
    private $username, $password;
    private $token;
    private $tokenRenewed = NULL;


    public function __construct($guzzleConfig, $username, $password, callable $getGuid)
    {
        $handler = \GuzzleHttp\HandlerStack::create();

        $handler->push(Middleware::mapRequest(function (\Psr\Http\Message\RequestInterface $request) {
            return $request->withHeader('Authorization-Token', $this->token);
        }));

        $handler->push(Middleware::mapResponse(function (\Psr\Http\Message\ResponseInterface $response) use ($username, $password, $getGuid) {
            $statusCode = $response->getStatusCode();

            // If an invalid session authorization token is supplied, HTTP response code 401 (Unauthorized) is returned.
            // 403 is undocumented :D
            if ($this->tokenRenewed !== TRUE && ($statusCode == 401 || $statusCode == 403)) {
                $this->tokenRenewed = TRUE;
                $this->token = $getGuid($username, $password);
            }

            return $response;
        }));

        $guzzleConfig['handler'] = $handler;
        $this->client = new \GuzzleHttp\Client($guzzleConfig);
        $this->username = $username;
        $this->password = $password;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }


    public function get($url, array $data = [])
    {
        try {
            return $this->processRequest('get', $url, $data);
        } catch(\Exception $e) {
            // send request again, token refreshed automatically via mapRequest middleware
            if($e->getCode() === 403) {
                return $this->processRequest('get', $url, $data);
            }
        }
    }

    public function post($url, array $data = [])
    {
        try {
            return $this->processRequest('post', $url, $data);
        } catch(\Exception $e) {
            // send request again, token refreshed automatically via mapRequest middleware
            if($e->getCode() === 403) {
                return $this->processRequest('get', $url, $data);
            }
        }

    }


    private function processRequest($method, $url, $data = [])
    {
        $result = (object) [
            'success' => FALSE,
            'data'    => NULL,
            'errors'  => []
        ];

        $httpStatusCode = 0;

        try {
            $response = NULL;
            if ($method === 'post') {
                //$this->logger->debug("Sending POST to {$url}", (array) $data);
                echo "Sending POST to {$url} \n";
                $response = $this->client->post("{$url}", ['json' => $data]);
            } else {
                //$this->logger->debug("Sending GET to {$url}");
                if (count($data) > 0) {
                    $q = http_build_query($data);
                    echo "Sending GET to {$url}?{$q} \n";
                    $response = $this->client->get("{$url}", ['query' => $data]);
                } else {
                    echo "Sending GET to {$url} \n";
                    $response = $this->client->get("{$url}");
                }
            }

            if (($httpStatusCode = $response->getStatusCode()) == 200) {
                $content = $response->getBody()->getContents();
                $result->data = json_decode($content);
                $errors = [];
                if (is_object($result->data) || is_array($result->data)) {
                    $result->success = TRUE;
                } else if (is_string($result->data)) {
                    $result->success = TRUE;
                } else if ($response === NULL) {
                    $result->success = TRUE;
                    $response->data = new \stdClass;
                } else {
                    $response->errors[] = 'API error: unexpected response structure.';
                }
            } else if ($httpStatusCode > 300 && $httpStatusCode < 400) {
                $response->errors[] = "API notice: redirected ({$httpStatusCode})";
            } else {
                $response->errors[] = 'API status error: code ' . $httpStatusCode;
                return $response;
            }

        } catch(\GuzzleHttp\Exception\ClientException $e) {
            $request = $e->getRequest();
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $httpStatusCode = $response->getStatusCode();
                if ($httpStatusCode == 400) {
                    // bad request (missing headers, bad data structure, whatever)
                } else if ($httpStatusCode == 403) {
                    // access denied (invalid token)

                    throw new \Exception ('Access denied', 403);
                }
            }
        }

        if (!isset($result->success)) {
            $result->success = FALSE;
        }

        return $result;
    }



}