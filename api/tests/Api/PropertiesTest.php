<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Property;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class PropertiesTest extends ApiTestCase {
    use RefreshDatabaseTrait;

    public function testGetProperties() {
        $response = static::createClient()->request('GET', '/properties');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testCreateProperty() {
        $response = static::createClient()->request('POST', '/properties', ['json' => [
            'region' => 'Auvergne-Rhône-Alpes',
            'surface' => 100,
            'price' => 500000,
            'day' => '10',
            'month' => '02',
            'year' => '2021',
            'count' => 666,
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/contexts/Property',
            '@type' => 'Property',
            'region' => 'Auvergne-Rhône-Alpes',
            'surface' => 100,
            'price' => 500000,
            'day' => '10',
            'month' => '02',
            'year' => '2021',
            'count' => 666,
        ]);
    }
}
