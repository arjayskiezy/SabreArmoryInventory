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

        // Create 12 fake users
        for ($i = 0; $i < 12; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->safeEmail);
            $user->setRoles(['ROLE_USER']);
            $user->setFullName($faker->name()); // <-- set full_name
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);



            $manager->persist($user);
        }

        // Specific demo user
        $demo = new User();
        $demo->setEmail('user@example.com');
        $demo->setRoles(['ROLE_USER']);
        $demo->setFullName('Demo User'); // <-- set full_name
        $demo->setPassword(
            $this->passwordHasher->hashPassword($demo, 'userpass123')
        );
        $manager->persist($demo); // <-- persist the demo user

        // Admin account
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setFullName('Administrator'); // <-- set full_name
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'adminpass123')
        );
        $manager->persist($admin);

        $manager->flush();
    }
}
