<?php

namespace App\DataFixtures;

use App\Entity\Stock;
use App\Entity\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StockFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $stockData = [
            101 => [
                'Assault Rifle' => 75,
                'Submachine Gun' => 90,
                'Light Machine Gun' => 40,
                'Carbine' => 65,
            ],
            102 => [
                'Sniper Rifle' => 40,
                'Designated Marksman Rifle' => 55,
            ],
            103 => [
                'Rocket Launcher' => 20,
                'Grenade Launcher' => 25,
                'Flamethrower' => 15,
            ],
            104 => [
                'Pistol' => 120,
                'Shotgun' => 70,
                'Combat Knife' => 200,
            ],
            105 => [
                'Tactical Shield' => 30,
            ],
        ];

        foreach ($stockData as $typeId => $units) {
            foreach ($units as $unitName => $quantity) {
                $unit = $manager->getRepository(Unit::class)->findOneBy([
                    'name' => $unitName,
                ]);

                if (!$unit) {
                    throw new \Exception("No Unit found with name '{$unitName}' (Type ID {$typeId}). Did you load UnitFixtures first?");
                }

                $stock = new Stock();
                $stock->setUnit($unit);
                $stock->setQuantity($quantity);

                $manager->persist($stock);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UnitFixtures::class,
        ];
    }
}
