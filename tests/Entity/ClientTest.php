<?php

namespace App\Tests\Entity;

use App\Entity\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testClientCreation(): void
    {
        $client = new Client();
        $client->setFirstname("Thomas");
        $client->setLastname("Steiner");
        $client->setEmail("Thomas@steiner.fr");
        $client->setPhoneNumber("0989238736");
        $client->setAddress("9 rue de Metz");

        $this->assertEquals("Thomas", $client->getFirstname());
        $this->assertEquals("Steiner", $client->getLastname());
        $this->assertEquals("Thomas@steiner.fr", $client->getEmail());
        $this->assertEquals("0989238736", $client->getPhoneNumber());
        $this->assertEquals("9 rue de Metz", $client->getAddress());
    }
}