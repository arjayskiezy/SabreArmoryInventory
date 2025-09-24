<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create 10 fake users
        for ($i = 0; $i < 12; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->safeEmail);
            $user->setRoles(['ROLE_USER']);

            // Hash password (use 'password' for all fake accounts)
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setRoles(['ROLE_USER']);
        $plainPasswordUser = 'userpass123';
        $hashedPasswordUser = $this->passwordHasher->hashPassword($user, $plainPasswordUser);
        $user->setPassword($hashedPasswordUser);
        $manager->persist($user);

        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $plainPasswordAdmin = 'adminpass123';
        $hashedPasswordAdmin = $this->passwordHasher->hashPassword($admin, $plainPasswordAdmin);
        $admin->setPassword($hashedPasswordAdmin);
        $manager->persist($admin);


        $manager->flush();
    }
}
