<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client = new Client();
        $client->setFirstname('John')
            ->setLastname('Doe')
            ->setEmail('john.doe@example.com')
            ->setPhoneNumber('123456789')
            ->setAddress('123 Street, City, Country')
            ->setCreatedAt(new \DateTimeImmutable('2024-09-20'));

        $manager->persist($client);
        $manager->flush();
    }
}
