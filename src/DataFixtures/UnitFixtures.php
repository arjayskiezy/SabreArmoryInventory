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
            // Type 101: Rifles
            [
                'name' => 'M4A1',
                'description' => 'Standard issue assault rifle, highly versatile.',
                'price' => 1500,
                'image_path' => 'images/m4a1.jpg',
                'type' => 101,
            ],
            [
                'name' => 'HK416',
                'description' => 'High-performance assault rifle by Heckler & Koch.',
                'price' => 1800,
                'image_path' => 'images/hk416.jpg',
                'type' => 101,
            ],
            [
                'name' => 'MK18',
                'description' => 'Compact variant of the M4 for close-quarter battles.',
                'price' => 1600,
                'image_path' => 'images/mk18.jpg',
                'type' => 101,
            ],
            [
                'name' => 'AK-47',
                'description' => 'Classic automatic rifle known for reliability.',
                'price' => 1400,
                'image_path' => 'images/ak47.jpg',
                'type' => 101,
            ],

            // Type 102: Snipers
            [
                'name' => 'M24',
                'description' => 'Bolt-action sniper rifle, used for long-range precision.',
                'price' => 3000,
                'image_path' => 'images/m24.jpg',
                'type' => 102,
            ],
            [
                'name' => 'AWP',
                'description' => 'High-caliber sniper rifle for extreme range.',
                'price' => 3500,
                'image_path' => 'images/awp.jpg',
                'type' => 102,
            ],
            [
                'name' => 'Barrett M82',
                'description' => 'Anti-material sniper rifle with explosive rounds.',
                'price' => 5000,
                'image_path' => 'images/barrett_m82.jpg',
                'type' => 102,
            ],

            // Type 103: Machine Guns
            [
                'name' => 'M249 SAW',
                'description' => 'Light machine gun for sustained fire support.',
                'price' => 2500,
                'image_path' => 'images/m249_saw.jpg',
                'type' => 103,
            ],
            [
                'name' => 'PKM',
                'description' => 'Soviet-designed general-purpose machine gun.',
                'price' => 2700,
                'image_path' => 'images/pkm.jpg',
                'type' => 103,
            ],
            [
                'name' => 'FN MAG',
                'description' => 'Reliable machine gun for infantry and vehicle support.',
                'price' => 3000,
                'image_path' => 'images/fn_mag.jpg',
                'type' => 103,
            ],

            // Type 104: Pistols
            [
                'name' => 'Glock 17',
                'description' => 'Popular semi-automatic pistol, reliable and easy to use.',
                'price' => 500,
                'image_path' => 'images/glock17.jpg',
                'type' => 104,
            ],
            [
                'name' => 'Beretta 92FS',
                'description' => 'Classic sidearm used by military forces worldwide.',
                'price' => 550,
                'image_path' => 'images/beretta92fs.jpg',
                'type' => 104,
            ],
            [
                'name' => 'Sig Sauer P226',
                'description' => 'Durable and accurate pistol for professional use.',
                'price' => 600,
                'image_path' => 'images/sig_p226.jpg',
                'type' => 104,
            ],

            // Type 105: Shotguns
            [
                'name' => 'Remington 870',
                'description' => 'Pump-action shotgun used for close-range combat.',
                'price' => 900,
                'image_path' => 'images/remington870.jpg',
                'type' => 105,
            ],
            [
                'name' => 'Mossberg 500',
                'description' => 'Reliable pump-action shotgun for tactical operations.',
                'price' => 850,
                'image_path' => 'images/mossberg500.jpg',
                'type' => 105,
            ],
            [
                'name' => 'Benelli M4',
                'description' => 'Semi-automatic combat shotgun for military use.',
                'price' => 1200,
                'image_path' => 'images/benelli_m4.jpg',
                'type' => 105,
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
