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
            101 => ['name' => 'Rifle',],
            102 => ['name' => 'Sniper',],
            103 => ['name' => 'Machine Gun',],
            104 => ['name' => 'Pistol',],
            105 => ['name' => 'Shotgun',],
        ];

        foreach ($types as $id => $data) {
            $type = new UnitType();
            $type->setId($id);
            $type->setName($data['name']);
            $manager->persist($type);
        }


        $manager->flush();
    }
}
