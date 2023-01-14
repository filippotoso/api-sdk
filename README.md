# Api SDK

A lightweight SDK to develop REST API clients

## Installing

Use Composer to install it:

```
composer require filippo-toso/api-sdk
```

## How does it work?

The best way is to start with an example. Let's build an SDK for Windy.com APIs.

First we build the main class:

```php
use FilippoToso\Api\Sdk\Sdk;

class Windy extends Sdk
{
    public function list(): ListEndpoint
    {
        return new ListEndpoint($this);
    }
}

```

The `Windy` exposes a `list()` method that implements the https://api.windy.com/webcams/docs#/list calls.
You can implement how many endpoints you want (i.e. `map`).

Then let's write the code to call the endpoint:

```php
use FilippoToso\Api\Sdk\Endpoint;
use FilippoToso\Api\Sdk\Support\Response;

class ListEndpoint extends Endpoint
{
    public function nearby($latitude, $longitude, $radius = 10): Response
    {
        return $this->get('/list/nearby=' . $latitude . ',' . $longitude . ',' . $radius . '?' . http_build_query($this->params([
            'show' => 'webcams:location,image',
        ])));
    }
}
```

The ListEndpoint exposes the nearby() methods. An endpoint class can expose as many methods as you need (i.e. one for each REST call).

Finally, let's call the service:

```php
use FilippoToso\Api\Sdk\Support\Options;

include(__DIR__ . '/../vendor/autoload.php');

include(__DIR__ . '/ListEndpoint.php');
include(__DIR__ . '/Windy.php');

$options = new Options([
    'uri' => 'https://api.windy.com/api/webcams/v2',
    'headers' => [
        'x-windy-key' => '...',
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ],
]);

$windy = new Windy($options);

$response = $windy->list()->nearby(45.9035644, 13.3038818, 10);

print_r($response->body());
```

The Options class allows you to specify multiple options (i.e. a production Vs. development url). Check the sources for more information on all the options available.

That's it. A flexible and clean implementation of an API SDK.

## Source of inspiration

This SDK is heavily inspired by this article series:

https://madewithlove.com/blog/software-engineering/building-an-sdk-with-php-part-1/

