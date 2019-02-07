<?php

namespace Esler\JsonRpc;

use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{

    function testExtendedJsonResult() {
        $json = '{"id":123, "result": {"lastModified": {"__jsonclass__": ["datetime", "2018-09-11T07:50:32"]}}}';

        $response = Response::fromJson($json);
        $this->assertInstanceOf(Response::class, $response);

        $result = $response->getResult();
        $this->assertInstanceOf('stdClass', $result);
        $this->assertObjectHasAttribute('lastModified', $result);

        $lastModified = $result->lastModified;
        $this->assertInstanceOf('DateTime', $lastModified);
        $this->assertSame(1536652232, $lastModified->getTimestamp());
    }

    function testExtendedJsonResultNested() {
        $json = '{"id":123, "result": [{"lastModified": {"__jsonclass__": ["datetime", "2018-09-11T07:50:32"]}}]}';

        $response = Response::fromJson($json);
        $this->assertInstanceOf(Response::class, $response);

        $result = $response->getResult();
        $this->assertIsArray($result);
        foreach ($result as $value) {
            $this->assertInstanceOf('stdClass', $value);
            $this->assertObjectHasAttribute('lastModified', $value);

            $lastModified = $value->lastModified;
            $this->assertInstanceOf('DateTime', $lastModified);
            $this->assertSame(1536652232, $lastModified->getTimestamp());
        }
    }

}
