<?php

namespace Esler;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Promise;
use Esler\JsonRpc\Request;
use Esler\JsonRpc\Response;

use function GuzzleHttp\json_decode;

class JsonRpc {

    private $client;
    private $namespace;

    public function __construct(Client $client, string $namespace='') {
        $this->client = $client;
        $this->namespace = $namespace;
    }

    public function send(Request $request): Promise {
        $promise = $this->client->requestAsync('POST', '', ['json' => $request]);

        return $promise->then(
            function ($response) {
                return Response::fromJson($response->getBody());
            }
        );
    }

    public function request(string $name, array $params=[], string $id=null): Promise {
        return $this->send(new Request($name, $params, $id ?? $this->generateId()));
    }

    public function __get(string $name): JsonRpc {
        return new JsonRpc($this->client, $name . '.');
    }

    public function __call(string $name, array $params) {
        $response = $this->request($this->namespace . $name, $params)->wait();

        if ($error = $response->getError()) {
            throw $error;
        }

        return $response->getResult();
    }

    protected function generateId(): string {
        return \bin2hex(\random_bytes(16));
    }

}
