<?php

namespace App\Tests\Resources;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use PHPUnit\Framework\Attributes\Test;

class UserResourceTest extends ApiTestCase
{

    public function testCreateUser(): void
    {
        $client = static::createClient();
        $response = $client->request('POST', '/api/users', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => [
                'firstName' => 'Roger',
                'lastName' => 'Rabbit',
                'dateOfBirth' => '1988-06-22',
                'email' => 'roger.rabbit@toontown.com'
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonData = $response->toArray(throw: false);
        $this->assertArrayHasKey('@context', $jsonData);
        $this->assertArrayHasKey('@id', $jsonData);
        $this->assertArrayHasKey('@type', $jsonData);
        $this->assertEquals('Roger', $jsonData['firstName']);
        $this->assertEquals('Rabbit', $jsonData['lastName']);
        $this->assertEquals('1988-06-22', $jsonData['dateOfBirth']);
        $this->assertEquals('roger.rabbit@toontown.com', $jsonData['email']);

        $this->assertMatchesResourceItemJsonSchema(User::class);
    }


    #[Test]
    public function testCreateUserWithMissingFirstName(): void
    {
        $client = static::createClient();
        $response = $client->request('POST', '/api/users', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => [
                'lastName' => 'Rabbit',
                'dateOfBirth' => '1988-06-22',
                'email' => 'roger.rabbit@toontown.com'
            ]
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');

        $jsonData = $response->toArray(throw: false);
        $this->assertArrayHasKey('violations', $jsonData);
        $this->assertCount(1, $jsonData['violations']);
        $this->assertEquals('firstName', $jsonData['violations'][0]['propertyPath']);
        $this->assertStringContainsString('firstName is a required field', $jsonData['violations'][0]['message']);
    }

    #[Test]
    public function testCreateUserWithMissingLastName(): void
    {
        $client = static::createClient();
        $response = $client->request('POST', '/api/users', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => [
                'firstName' => 'Roger',
                'dateOfBirth' => '1988-06-22',
                'email' => 'roger.rabbit@toontown.com'
            ]
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');

        $jsonData = $response->toArray(throw: false);
        $this->assertArrayHasKey('violations', $jsonData);
        $this->assertCount(1, $jsonData['violations']);
        $this->assertEquals('lastName', $jsonData['violations'][0]['propertyPath']);
        $this->assertStringContainsString('lastName is a required field', $jsonData['violations'][0]['message']);
    }

    public function testCreateUserWithMissingDateOfBirth(): void
    {
        $client = static::createClient();
        $response = $client->request('POST', '/api/users', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => [
                'firstName' => 'Roger',
                'lastName' => 'Rabbit',
                'email' => 'roger.rabbit@toontown.com'
            ]
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');

        $jsonData = $response->toArray(throw: false);
        $this->assertArrayHasKey('violations', $jsonData);
        $this->assertCount(1, $jsonData['violations']);
        $this->assertEquals('dateOfBirth', $jsonData['violations'][0]['propertyPath']);
        $this->assertStringContainsString('dateOfBirth is a required field', $jsonData['violations'][0]['message']);
    }

    public function testCreateUserWithBlankEmail(): void
    {
        $client = static::createClient();
        $response = $client->request('POST', '/api/users', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => [
                'firstName' => 'Roger',
                'lastName' => 'Rabbit',
                'dateOfBirth' => '1988-06-22',
                'email' => ''
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonData = $response->toArray(throw: false);

        $this->assertArrayHasKey('@context', $jsonData);
        $this->assertArrayHasKey('@id', $jsonData);
        $this->assertArrayHasKey('@type', $jsonData);
        $this->assertEquals('Roger', $jsonData['firstName']);
        $this->assertEquals('Rabbit', $jsonData['lastName']);
        $this->assertEquals('1988-06-22', $jsonData['dateOfBirth']);
        $this->assertEquals('', $jsonData['email']);

        $this->assertMatchesResourceItemJsonSchema(User::class);
    }

    public function testCreateUserWithMissingEmail(): void
    {
        $client = static::createClient();
        $response = $client->request('POST', '/api/users', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => [
                'firstName' => 'Roger',
                'lastName' => 'Rabbit',
                'dateOfBirth' => '1988-06-22'
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonData = $response->toArray(throw: false);

        $this->assertArrayHasKey('@context', $jsonData);
        $this->assertArrayHasKey('@id', $jsonData);
        $this->assertArrayHasKey('@type', $jsonData);
        $this->assertEquals('Roger', $jsonData['firstName']);
        $this->assertEquals('Rabbit', $jsonData['lastName']);
        $this->assertEquals('1988-06-22', $jsonData['dateOfBirth']);
        $this->assertArrayNotHasKey('email', $jsonData);

        $this->assertMatchesResourceItemJsonSchema(User::class);
    }

    public function testCreateUserWithInvalidEmailFails(): void
    {
        $client = static::createClient();
        $response = $client->request('POST', '/api/users', [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => [
                'firstName' => 'Roger',
                'lastName' => 'Rabbit',
                'dateOfBirth' => '1988-06-22',
                'email' => 'ohNoesThisIsNotAnEmailAddress'
            ]
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');

        $jsonData = $response->toArray(throw: false);
        $this->assertArrayHasKey('violations', $jsonData);
        $this->assertCount(1, $jsonData['violations']);
        $this->assertEquals('email', $jsonData['violations'][0]['propertyPath']);
        $this->assertStringContainsString('This value is not a valid email address.', $jsonData['violations'][0]['message']);
    }
}
