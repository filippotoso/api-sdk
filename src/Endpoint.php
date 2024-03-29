<?php

namespace FilippoToso\Api\Sdk;

use Exception;
use FilippoToso\Api\Sdk\Support\Client;
use FilippoToso\Api\Sdk\Support\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * @method Response get($uri, array $headers = [])
 * @method Response head($uri, array $headers = [])
 * @method Response trace($uri, array $headers = [])
 * @method Response post($uri, array $headers = [], $body = null)
 * @method Response put($uri, array $headers = [], $body = null)
 * @method Response patch($uri, array $headers = [], $body = null)
 * @method Response delete($uri, array $headers = [], $body = null)
 * @method Response options($uri, array $headers = [], $body = null)
 * @method Response send(string $method, $uri, array $headers = [], $body = null) 
 */
class Endpoint
{
    protected Sdk $sdk;

    protected Client $client;

    /**
     * Constructor
     *
     * @param Sdk $sdk
     */
    public function __construct(Sdk $sdk)
    {
        $this->sdk = $sdk;
        $this->client = new Client($sdk);
    }

    /**
     * Parse response
     *
     * @param ResponseInterface $response
     * @return Response
     */
    protected function parse(ResponseInterface $response): Response
    {
        return Response::make($response);
    }

    /**
     * Merge options parameters
     *
     * @param array $params
     * @return array
     */
    protected function params(array $params)
    {
        return array_merge($this->sdk->options()->params(), $params);
    }

    /**
     * Forward calls to the client
     *
     * @param string $name
     * @param mixed $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->client, $name], $arguments);
    }
}
