<?php

namespace FilippoToso\Api\Sdk;

use Exception;
use FilippoToso\Api\Sdk\Support\Response;
use Http\Client\Common\HttpMethodsClientInterface;
use Psr\Http\Message\ResponseInterface;

// TODO: Add methods documentation

class Endpoint
{
    private Sdk $sdk;

    public function __construct(Sdk $sdk)
    {
        $this->sdk = $sdk;
    }

    protected function parse(ResponseInterface $response): Response
    {
        return Response::make($response);
    }

    protected function params(array $params)
    {
        return array_merge($this->sdk->options()->params(), $params);
    }

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
