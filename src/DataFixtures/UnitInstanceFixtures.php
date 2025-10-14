<?php

namespace App\DataFixtures;

use App\Entity\UnitInstance;
use App\Entity\Unit;
use App\Entity\User;
use App\Enum\UnitStatus;
use App\Enum\ItemStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UnitInstanceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Fetch all Units and Users from the database
        $units = $manager->getRepository(Unit::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        if (empty($units)) {
            throw new \Exception("No Unit entities found. Make sure UnitFixtures ran first.");
        }
        if (empty($users)) {
            throw new \Exception("No User entities found. Make sure UserFixtures ran first.");
        }

        // Create 20 UnitInstances
        for ($i = 0; $i < 20; $i++) {
            $unitInstance = new UnitInstance();

            // Randomly pick a Unit and a User
            $unitInstance->setWeaponType($faker->randomElement($units));
            $unitInstance->setOwner($faker->randomElement($users));

            // Serial number: 10-character alphanumeric
            $unitInstance->setSerialNumber(strtoupper($faker->bothify('??###??###')));

            // Random purchased date within last 5 years
            $unitInstance->setPurchasedDate(
                \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-5 years', 'now'))
            );

            // Random status
            $unitInstance->setStatus($faker->randomElement([
                UnitStatus::ACTIVE,
                UnitStatus::DAMAGED,
                UnitStatus::RETIRED,
            ]));

            // Random itemStatus
            $unitInstance->setItemStatus($faker->randomElement([
                ItemStatus::GOOD,
                ItemStatus::MAINTENANCE,
                ItemStatus::LOW_AMMO,
                ItemStatus::URGENT,
            ]));

            $manager->persist($unitInstance);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UnitFixtures::class,
            UserFixtures::class,
        ];
    }
}
