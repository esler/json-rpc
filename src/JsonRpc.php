<?php

namespace Esler;

use GuzzleHttp\Client;
use Esler\JsonRpc\Request;

use function GuzzleHttp\json_decode;

class JsonRpc {

    private $client;
    private $namespace;

    public function __construct(Client $client, string $namespace='') {
        $this->client = $client;
        $this->namespace = $namespace;
    }

    public function call(Request $request): \stdClass {
        $response = $this->client->request('POST', '', ['json' => $request]);

        return json_decode($response->getBody()->getContents());
    }

    public function __get(string $name): JsonRpc {
        return new JsonRpc($this->client, $name . '.');
    }

    public function __call(string $name, array $params): \stdClass {
        return $this->call(new Request($this->namespace . $name, $params, $this->generateId()));
    }

    protected function generateId(): string {
        return bin2hex(random_bytes(16));
    }

}
