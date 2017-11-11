<?php

namespace Spatie\Snapshots\Test\Unit\Drivers;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\Drivers\JsonSchemaDriver;

class JsonSchemaDriverTest extends TestCase
{
    private $validResponse;

    private $invalidResponse;

    private $schema;

    protected function setUp()
    {
        $this->validResponse = json_encode([
            'foo' => 'bar',
        ]);

        $this->invalidResponse = json_encode([
            'foo'  => 'bar',
            'foo2' => 'bar2',
        ]);

        $this->schema = json_encode([
            'type'                 => 'object',
            'properties'           => [
                'foo' => [
                    'type' => 'string',
                ],
            ],
            'additionalProperties' => false,
            'required'             => ['foo'],

        ]);
    }

    /** @test */
    public function it_can_serialize_to_json_schema()
    {
        $driver = new JsonSchemaDriver();
        $this->assertEquals($this->schema, $driver->serialize($this->validResponse));
    }

    /** @test */
    public function it_allows_valid_response()
    {
        $driver = new JsonSchemaDriver();
        $driver->match($this->schema, $this->validResponse);
    }

    /** @test */
    public function it_does_not_allow_invalid_response()
    {
        $driver = new JsonSchemaDriver();
        $this->expectException(ExpectationFailedException::class);
        $driver->match($this->schema, $this->invalidResponse);
    }
}
