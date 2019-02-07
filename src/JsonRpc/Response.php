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

            if ($object->result) {
                self::convertJsonClass($object->result);
            }

            return new Response($object->result, $error, $object->id);
        }

        throw new Exception(\json_last_error_msg());
    }

    private static function convertJsonClass(&$result): void {
        $isArray = is_array($result);
        foreach ($result as &$value) {
            if ($isArray) {
                self::convertJsonClass($value);
            } elseif (is_object($value) && isset($value->__jsonclass__)) {
                switch ($value->__jsonclass__[0]) {
                    case 'datetime':
                        $value = new \DateTime($value->__jsonclass__[1], new \DateTimeZone('UTC'));
                        break;
                }
            }
        }
    }
}
