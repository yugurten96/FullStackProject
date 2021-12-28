<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class PropertyTest extends ApiTestCase
{

    public function testGetAverage()
    {
        $response = static::createClient()->request('GET', '/property/average');

        $this->assertResponseStatusCodeSame(200);
    }
}
