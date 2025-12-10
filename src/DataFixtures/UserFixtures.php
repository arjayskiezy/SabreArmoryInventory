<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Rank;
use App\Entity\UnitInstance;
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

        // Get existing ranks from DB
        $ranks = $manager->getRepository(Rank::class)->findAll();
        if (!$ranks) {
            throw new \Exception("No ranks found. Please load Rank fixtures first.");
        }

        // Create 12 fake users
        for ($i = 0; $i < 12; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->safeEmail);
            $user->setRoles(['ROLE_USER', 'ROLE_CUSTOMER']);
            $user->setFullName($faker->name());
            $user->setUserRank($ranks[array_rand($ranks)]);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));

            // Set contact number and bio
            $user->setContactNumber($faker->phoneNumber);
            $user->setBio($faker->sentence(12));

            $manager->persist($user);
        }

        // Demo user
        $demo = new User();
        $demo->setEmail('user@example.com');
        $demo->setRoles(['ROLE_USER', 'ROLE_CUSTOMER']);
        $demo->setFullName('Demo User');
        $demo->setUserRank($ranks[array_rand($ranks)]);
        $demo->setPassword($this->passwordHasher->hashPassword($demo, 'userpass123'));
        $demo->setContactNumber($faker->phoneNumber);
        $demo->setBio($faker->sentence(12));
        $manager->persist($demo);

        // Admin user
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $admin->setFullName('Administrator');
        $admin->setUserRank($ranks[array_rand($ranks)]);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'adminpass123'));
        $admin->setContactNumber($faker->phoneNumber);
        $admin->setBio($faker->sentence(12));
        $manager->persist($admin);

        $manager->flush();
    }
}
