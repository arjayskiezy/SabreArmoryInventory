<?php

namespace App\DataFixtures;

use App\Entity\UnitType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UnitTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $types = [
            101 => ['name' => 'Rifle', 'quantity' => 50],
            102 => ['name' => 'Sniper', 'quantity' => 20],
            103 => ['name' => 'Machine Gun', 'quantity' => 10],
            104 => ['name' => 'Pistol', 'quantity' => 100],
            105 => ['name' => 'Shotgun', 'quantity' => 30],
        ];

        foreach ($types as $id => $data) {
            $type = new UnitType();
            $type->setId($id);
            $type->setName($data['name']);
            $type->setQuantity($data['quantity']); 
            $manager->persist($type);
        }


        $manager->flush();
    }
}
