<?php

namespace Esler\JsonRpc;

class Request implements \JsonSerializable
{

    private $id;
    private $method;
    private $params;

    public function __construct(string $method, array $params, string $id)
    {
        $this->method = $method;
        $this->params = $params;
        $this->id = $id;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'     => $this->id,
            'method' => $this->method,
            'params' => $this->params,
        ];
    }
}
