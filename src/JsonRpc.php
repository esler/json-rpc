<?php

namespace Esler;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\RejectedPromise;
use Esler\JsonRpc\Request;
use Esler\JsonRpc\Response;

use function GuzzleHttp\json_decode;

class JsonRpc
{

    private $client;
    private $namespace;

    public function __construct(Client $client, string $namespace = '')
    {
        $this->client = $client;
        $this->namespace = $namespace;
    }

    // Promise of Response
    public function send(Request $request): Promise
    {
        return $this->client->requestAsync('POST', '', ['json' => $request])
            ->then(
                function ($response) {
                    return Response::fromJson($response->getBody());
                }
            );
    }

    // Promise of Result (can be rejected)
    public function request(string $name, array $params = [], string $id = null): Promise
    {
        return $this->send(new Request($name, $params, $id ?? $this->generateId()))
            ->then(
                function (Response $response) {
                    if ($error = $response->getError()) {
                        return new RejectedPromise($error);
                    }

                    return new FulfilledPromise($response->getResult());
                }
            );
    }

    public function __get(string $name): JsonRpc
    {
        return new JsonRpc($this->client, $name . '.');
    }

    public function __call(string $name, array $params)
    {
        return $this->request($this->namespace . $name, $params)->wait();
    }

    protected function generateId(): string
    {
        return \bin2hex(\random_bytes(16));
    }
}
