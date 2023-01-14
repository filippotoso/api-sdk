<?php

namespace FilippoToso\Api\Sdk\Support;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Options
{
    private OptionsResolver $resolver;

    private array $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;

        $this->resolver = new OptionsResolver();

        $this->configureOptions();

        $this->setAllowedTypes();
    }

    private function setAllowedTypes(): void
    {
        $this->resolver->setAllowedTypes('client_builder', ClientBuilder::class);
        $this->resolver->setAllowedTypes('uri_factory', UriFactoryInterface::class);
        $this->resolver->setAllowedTypes('headers', 'array');
        $this->resolver->setAllowedTypes('params', 'array');
        $this->resolver->setAllowedTypes('uri', 'string');
    }

    private function configureOptions(): void
    {
        $this->resolver->setDefaults([
            'client_builder' => new ClientBuilder(),
            'uri_factory' => Psr17FactoryDiscovery::findUriFactory(),
            'uri' => null,
            'params' => [],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    protected function options()
    {
        return $this->resolver->resolve($this->options);
    }

    /**
     * Client builder
     *
     * @return ClientBuilder
     */
    public function clientBuilder(): ClientBuilder
    {
        return $this->options()['client_builder'];
    }

    /**
     * Uri factory
     *
     * @return UriFactoryInterface
     */
    public function uriFactory(): UriFactoryInterface
    {
        return $this->options()['uri_factory'];
    }

    /**
     * Headers used in the request
     *
     * @return array
     */
    public function headers()
    {
        return $this->options()['headers'];
    }

    /**
     * Query parameters that are always append to urls
     *
     * @return array
     */
    public function params()
    {
        return $this->options()['params'];
    }

    /**
     * Base uri
     *
     * @return string
     */
    public function uri()
    {
        return $this->options()['uri'];
    }
}
