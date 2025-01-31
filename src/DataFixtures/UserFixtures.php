<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $users = [
            ['email' => 'gwendolinedardari7@gmail.com', 'password' => 'azerty', 'firstname' => 'gwendoline', 'lastname' => 'dardari', 'roles' => ['ROLE_ADMIN']],
            ['email' => 'thomasphilip@gmail.com', 'password' => 'tomtom', 'firstname' => 'thomas', 'lastname' => 'philip', 'roles' => ['ROLE_MANAGER']],
            ['email' => 'test@gmail.com', 'password' => 'toto', 'firstname' => 'test', 'lastname' => 'toto', 'roles' => ['ROLE_USER']],
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setRoles($userData['roles']);
            $user->setFirstname($userData['firstname']); 
            $user->setLastname($userData['lastname']); 

            // Hasher le mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($user, $userData['password']);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
?>