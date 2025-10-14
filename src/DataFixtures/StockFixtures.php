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
        $units = $manager->getRepository(Unit::class)->findAll();

        foreach ($units as $unit) {
            $stock = new Stock();
            $stock->setUnit($unit) 
                  ->setQuantity(rand(5, 50)); 
            $manager->persist($stock);
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
