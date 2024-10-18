<?php

namespace App\Tests\Resources;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserResourceTest extends ApiTestCase
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
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

        $jsonData = $response->toArray();
        $this->assertArrayHasKey('@context', $jsonData);
        $this->assertArrayHasKey('@id', $jsonData);
        $this->assertArrayHasKey('@type', $jsonData);
        $this->assertEquals('Roger', $jsonData['firstName']);
        $this->assertEquals('Rabbit', $jsonData['lastName']);
        $this->assertEquals('1988-06-22', $jsonData['dateOfBirth']);
        $this->assertEquals('roger.rabbit@toontown.com', $jsonData['email']);

        $this->assertMatchesResourceItemJsonSchema(User::class);
    }
}
