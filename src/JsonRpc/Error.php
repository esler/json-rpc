<?php

namespace Esler\JsonRpc;

class Error extends \Error {
    private $data;

    public function __construct(string $message, int $code, array $data=null) {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    public function getData(): ?array {
        return $this->data;
    }

    public static function fromObject(object $object): Error {
        return new Error($object->message, $object->code, $object->data ?? null);
    }
}
