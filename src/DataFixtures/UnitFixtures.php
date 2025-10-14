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
            [
                'name' => 'Submachine Gun',
                'description' => 'Light automatic firearm effective for short to medium range.',
                'price' => 1200,
                'image_path' => 'images/submachine_gun.jpg',
                'type' => 101,
            ],
            [
                'name' => 'Shotgun',
                'description' => 'High-damage close-range weapon ideal for breaching.',
                'price' => 1000,
                'image_path' => 'images/shotgun.jpg',
                'type' => 104,
            ],
            [
                'name' => 'Light Machine Gun',
                'description' => 'Heavy automatic weapon designed for sustained fire.',
                'price' => 2500,
                'image_path' => 'images/light_machine_gun.jpg',
                'type' => 101,
            ],
            [
                'name' => 'Carbine',
                'description' => 'Compact rifle variant with balanced accuracy and handling.',
                'price' => 1400,
                'image_path' => 'images/carbine.jpg',
                'type' => 101,
            ],
            [
                'name' => 'Designated Marksman Rifle',
                'description' => 'Semi-automatic rifle optimized for precision at mid to long range.',
                'price' => 2200,
                'image_path' => 'images/dmr.jpg',
                'type' => 102,
            ],
            [
                'name' => 'Rocket Launcher',
                'description' => 'Anti-vehicle weapon capable of dealing explosive damage.',
                'price' => 5000,
                'image_path' => 'images/rocket_launcher.jpg',
                'type' => 103,
            ],
            [
                'name' => 'Grenade Launcher',
                'description' => 'Projectile weapon that launches explosive grenades for area damage.',
                'price' => 3500,
                'image_path' => 'images/grenade_launcher.jpg',
                'type' => 103,
            ],
            [
                'name' => 'Combat Knife',
                'description' => 'Close-quarters melee weapon, lightweight and silent.',
                'price' => 200,
                'image_path' => 'images/combat_knife.jpg',
                'type' => 104,
            ],
            [
                'name' => 'Tactical Shield',
                'description' => 'Provides protection against small arms fire during assaults.',
                'price' => 1800,
                'image_path' => 'images/tactical_shield.jpg',
                'type' => 105,
            ],
            [
                'name' => 'Flamethrower',
                'description' => 'Short-range incendiary weapon designed for area suppression.',
                'price' => 4000,
                'image_path' => 'images/flamethrower.jpg',
                'type' => 103,
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
