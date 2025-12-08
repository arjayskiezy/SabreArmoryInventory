<?php

namespace App\DataFixtures;

use App\Entity\Rank;
use App\Enum\UserStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RankFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ranks = [
            // Commissioned Officers
            ['name' => 'Lieutenant', 'abbr' => 'Lt', 'branch' => 'Army', 'category' => UserStatus::COMMISSIONED, 'desc' => 'Junior commissioned officer.'],
            ['name' => 'Captain', 'abbr' => 'Cpt', 'branch' => 'Army', 'category' => UserStatus::COMMISSIONED, 'desc' => 'Mid-level commissioned officer.'],
            ['name' => 'Major', 'abbr' => 'Maj', 'branch' => 'Army', 'category' => UserStatus::COMMISSIONED, 'desc' => 'Field-grade commissioned officer.'],

            // Non-Commissioned Officers
            ['name' => 'Sergeant', 'abbr' => 'Sgt', 'branch' => 'Army', 'category' => UserStatus::NON_COMMISSIONED, 'desc' => 'Supervises lower enlisted personnel.'],
            ['name' => 'Staff Sergeant', 'abbr' => 'SSgt', 'branch' => 'Army', 'category' => UserStatus::NON_COMMISSIONED, 'desc' => 'Leads squad-level units.'],

            // Enlisted Personnel
            ['name' => 'Private', 'abbr' => 'Pvt', 'branch' => 'Army', 'category' => UserStatus::ENLISTED, 'desc' => 'Entry-level enlisted member.'],
            ['name' => 'Private First Class', 'abbr' => 'PFC', 'branch' => 'Army', 'category' => UserStatus::ENLISTED, 'desc' => 'Junior enlisted soldier.'],
        ];

        foreach ($ranks as $r) {
            $rank = new Rank();
            $rank->setName($r['name'])
                ->setAbbreviation($r['abbr'])
                ->setBranch($r['branch'])
                ->setCategory($r['category'])
                ->setDescription($r['desc'])
                ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($rank);
        }

        $manager->flush();
    }
}
