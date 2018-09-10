<?php

namespace Esler\JsonRpc;

class Response
{
    private $result;
    private $error;
    private $id;

    public function __construct($result, ?Error $error, string $id)
    {
        $this->result = $result;
        $this->error = $error;
        $this->id = $id;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getError(): ?Error
    {
        return $this->error;
    }

    public static function fromJson(string $json): Response
    {
        if ($object = \json_decode($json)) {
            $error = isset($object->error) ? Error::fromObject($object->error) : null;

            return new Response($object->result, $error, $object->id);
        }

        throw new Exception(\json_last_error_msg());
    }
}
