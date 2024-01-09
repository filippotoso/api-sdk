<?php

namespace FilippoToso\Api\Sdk\Support;

use Exception;
use FilippoToso\Api\Sdk\Sdk;
use FilippoToso\Api\Sdk\Support\Response;
use Http\Client\Common\HttpMethodsClientInterface;
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
class Client
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
            if (in_array($name, ['post', 'put', 'patch', 'delete', 'options'])) {
                $arguments[2] = is_array($arguments[2]) ? json_encode($arguments) : $arguments[2];
            }

            return $this->parse(
                call_user_func_array([$this->sdk->client(), $name], $arguments)
            );
        }

        throw new Exception('Invalid method ' . __CLASS__ . '::' . $name);
    }
}
