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

    public function testCreatePropertyWithNoRegion() {
        $response = static::createClient()->request('POST', '/properties', ['json' => [
            'surface' => 10,
            'price' => 500000,
            'day' => '10',
            'month' => '02',
            'year' => '2021',
            'count' => 666,
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'region: This value should not be blank.',
        ]);
    }

    public function testCreatePropertyWithIncorrectSurface() {
        $response = static::createClient()->request('POST', '/properties', ['json' => [
            'region' => 'Auvergne-Rhône-Alpes',
            'surface' => 5,
            'price' => 500000,
            'day' => '10',
            'month' => '02',
            'year' => '2021',
            'count' => 666,
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'surface: This value should be between 9 and 1000.',
        ]);
    }

    public function testCreatePropertyWithIncorrectPrice() {
        $response = static::createClient()->request('POST', '/properties', ['json' => [
            'region' => 'Auvergne-Rhône-Alpes',
            'surface' => 10,
            'price' => 20,
            'day' => '10',
            'month' => '02',
            'year' => '2021',
            'count' => 666,
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'price: This value should be between 1000 and 1000000.',
        ]);
    }

    public function testCreatePropertyWithIncorrectDay() {
        $response = static::createClient()->request('POST', '/properties', ['json' => [
            'region' => 'Auvergne-Rhône-Alpes',
            'surface' => 10,
            'price' => 500000,
            'day' => '32',
            'month' => '02',
            'year' => '2021',
            'count' => 666,
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'day: This value should be between 1 and 31.',
        ]);
    }

    public function testCreatePropertyWithIncorrectMonth() {
        $response = static::createClient()->request('POST', '/properties', ['json' => [
            'region' => 'Auvergne-Rhône-Alpes',
            'surface' => 10,
            'price' => 500000,
            'day' => '10',
            'month' => '15',
            'year' => '2021',
            'count' => 666,
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'month: This value should be between 1 and 12.',
        ]);
    }

    public function testCreatePropertyWithIncorrectYear() {
        $response = static::createClient()->request('POST', '/properties', ['json' => [
            'region' => 'Auvergne-Rhône-Alpes',
            'surface' => 10,
            'price' => 500000,
            'day' => '10',
            'month' => '02',
            'year' => '2030',
            'count' => 666,
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'year: This value should be between 2017 and 2021.',
        ]);
    }

    public function testCreatePropertyWithIncorrectCount() {
        $response = static::createClient()->request('POST', '/properties', ['json' => [
            'region' => 'Auvergne-Rhône-Alpes',
            'surface' => 10,
            'price' => 500000,
            'day' => '10',
            'month' => '02',
            'year' => '2021',
            'count' => 1500,
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'count: This value should be between 0 and 1000.',
        ]);
    }

    public function testGetCount() {
        $response = static::createClient()->request('GET', '/property/count/month/1-1-2020/31-12-2020');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $body = json_decode($response->getContent(), true);
        $this->assertIsArray($body);

        foreach ($body['data'] as $row) {
            $this->assertArrayHasKey('key', $row);
            $this->assertArrayHasKey('value', $row);

            $month = $row['key'];
        }
    }

    public function testGetSellByYear() {
        $response = static::createClient()->request('GET', '/property/sell/2020');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $body = json_decode($response->getContent(), true);
        $this->assertIsArray($body);

        foreach ($body['data'] as $row) {
            $this->assertArrayHasKey('key', $row);
            $this->assertArrayHasKey('value', $row);
        }
    }

    public function testGetAverage() {
        $response = static::createClient()->request('GET', '/property/average');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $body = json_decode($response->getContent(), true);
        $this->assertIsArray($body);

        $years = ['2017', '2018', '2019', '2020', '2021'];

        foreach ($body['data'] as $row) {
            $this->assertArrayHasKey('key', $row);
            $this->assertArrayHasKey('value', $row);

            $yearName = $row['key'];
            $average =  (float) $row['value'];

            $valid = '';

            if (in_array($yearName, $years)) 
                $valid = $yearName;

            $this->assertThat(
                $yearName,
                $this->EqualTo($valid),
                "L'année doit être comprise entre 2017 et 2021"
            );

            $this->assertThat(
                $average,
                $this->greaterThan(0),
                "La moyenne par année doit être strictement positive"
            );
        }
    }
    
}
