<?php

namespace FilippoToso\Api\Sdk;

use Exception;
use FilippoToso\Api\Sdk\Support\Response;
use Http\Client\Common\HttpMethodsClientInterface;
use Psr\Http\Message\ResponseInterface;

class Endpoint
{
    protected Sdk $sdk;

    /**
     * Constructor
     *
     * @param Sdk $sdk
     */
    public function __construct(Sdk $sdk)
    {
        $this->sdk = $sdk;
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
        if (method_exists(HttpMethodsClientInterface::class, $name)) {
            return $this->parse(
                call_user_func_array([$this->sdk->client(), $name], $arguments)
            );
        }

        throw new Exception('Invalid method ' . __CLASS__ . '::' . $name);
    }
}
