<?php

namespace App\Tests\Api;

final class UsersTest extends AbstractTest
{
    public function testAdminResource()
    {
        $response = $this->createClientWithCredentials()->request('GET', '/users');
        $this->assertResponseIsSuccessful();
    }

    public function testLoginAsUser()
    {
        $token = $this->getToken([
            'username' => 'admin@admin.com',
            'password' => 'password',
        ]);

        $response = $this->createClientWithCredentials($token)->request('GET', '/users');
        $this->assertJsonContains(['hydra:description' => 'Access Denied.']);
        $this->assertResponseStatusCodeSame(403);
    }
}