<?php

namespace Spatie\Snapshots\Drivers;

use JsonSchema\Validator;
use PHPUnit\Framework\Assert;
use Spatie\Snapshots\Driver;
use Jedrzej\JtJS\Generator;

class JsonSchemaDriver implements Driver
{
    public function serialize($data): string
    {
        return json_encode(Generator::generate($data));
    }

    public function extension(): string
    {
        return 'schema.json';
    }

    public function match($expected, $actual)
    {
        $validator = new Validator();
        $data = json_decode($actual);
        Assert::assertEquals(0, $validator->validate($data, json_decode($expected)));
    }
}
