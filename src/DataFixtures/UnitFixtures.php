<?php

namespace App\DataFixtures;

use App\Entity\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UnitFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Example data
        $unitsData = [
            [
                'name' => 'Assault Rifle',
                'description' => 'High rate of fire rifle, suitable for medium-range combat.',
                'price' => 1500,
                'image_path' => 'images/assault_rifle.jpg',
            ],
            [
                'name' => 'Sniper Rifle',
                'description' => 'Long-range rifle with high accuracy.',
                'price' => 3000,
                'image_path' => 'images/sniper_rifle.jpg',
            ],
            [
                'name' => 'Pistol',
                'description' => 'Compact sidearm for close-quarters.',
                'price' => 500,
                'image_path' => 'images/pistol.jpg',
            ],
        ];

        foreach ($unitsData as $data) {
            $unit = new Unit();
            $unit->setName($data['name'])
                 ->setDescription($data['description'])
                 ->setPrice($data['price'])
                 ->setImagePath($data['image_path'])
                 ->setCreatedAt(new \DateTimeImmutable())
                 ->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($unit);
        }

        $manager->flush();
    }
}
