<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserCreation(): void
    {
        $user = new User();
        $user->setEmail("mathieu@philippi.com");
        $user->setFirstname("Mathieu");
        $user->setLastname("Philippi");
        $user->setRoles(["ROLE_MANAGER"]);
        $user->setPassword("hashed_password");

        $this->assertEquals("mathieu@philippi.com", $user->getEmail());
        $this->assertEquals("Mathieu", $user->getFirstname());
        $this->assertEquals("Philippi", $user->getLastname());
        $this->assertContains("ROLE_MANAGER", $user->getRoles());
        $this->assertEquals("hashed_password", $user->getPassword());
    }
}
