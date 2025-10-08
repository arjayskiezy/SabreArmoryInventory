<?php

namespace App\DataFixtures;

use App\Entity\Unit;
use App\Entity\UnitType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UnitFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $unitsData = [
            [
                'name' => 'Assault Rifle',
                'description' => 'High rate of fire rifle, suitable for medium-range combat.',
                'price' => 1500,
                'image_path' => 'images/assault_rifle.jpg',
                'type' => 101,
            ],
            [
                'name' => 'Sniper Rifle',
                'description' => 'Long-range rifle with high accuracy.',
                'price' => 3000,
                'image_path' => 'images/sniper_rifle.jpg',
                'type' => 102,
            ],
            [
                'name' => 'Pistol',
                'description' => 'Compact sidearm for close-quarters.',
                'price' => 500,
                'image_path' => 'images/pistol.jpg',
                'type' => 104,
            ],
        ];

        foreach ($unitsData as $data) {
            $unitType = $manager->getRepository(UnitType::class)->find($data['type']);
            if (!$unitType) {
                throw new \Exception("UnitType with id {$data['type']} not found");
            }

            $unit = new Unit();
            $unit->setName($data['name'])
                 ->setDescription($data['description'])
                 ->setPrice($data['price'])
                 ->setImagePath($data['image_path'])
                 ->setType($unitType);

            $manager->persist($unit);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UnitTypeFixtures::class,
        ];
    }
}
