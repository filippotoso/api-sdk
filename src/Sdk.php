<?php

namespace FilippoToso\Api\Sdk;

use FilippoToso\Api\Sdk\Support\ClientBuilder;
use FilippoToso\Api\Sdk\Support\Options;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Discovery\Psr17FactoryDiscovery;

class Sdk
{
    protected ClientBuilder $clientBuilder;
    protected Options $options;

    /**
     * Constructor
     *
     * @param Options $options
     */
    public function __construct(Options $options)
    {
        $this->options = $options;

        $this->clientBuilder = $this->options->clientBuilder() ?: new ClientBuilder();
        $uriFactory = $this->options->uriFactory() ?: Psr17FactoryDiscovery::findUriFactory();

        $this->clientBuilder->plugin(
            new BaseUriPlugin($uriFactory->createUri($this->options->uri()))
        );

        $this->clientBuilder->plugin(
            new HeaderDefaultsPlugin($this->options->headers())
        );
    }

    /**
     * Get the client
     *
     * @return HttpMethodsClientInterface
     */
    public function client(): HttpMethodsClientInterface
    {
        return $this->clientBuilder->client();
    }

    /**
     * Get the options
     *
     * @return Options
     */
    public function options(): Options
    {
        return $this->options;
    }
}
