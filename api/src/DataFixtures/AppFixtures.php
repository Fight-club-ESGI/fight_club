<?php

namespace App\DataFixtures;

use App\Entity\TicketCategory;
use App\Enum\TicketCategory\TicketCategoryEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new TicketCategory();
        $category->setName(TicketCategoryEnum::GOLD);

        $category2 = new TicketCategory();
        $category2->setName(TicketCategoryEnum::SILVER);

        $category3 = new TicketCategory();
        $category3->setName(TicketCategoryEnum::V_VIP);

        $category4 = new TicketCategory();
        $category4->setName(TicketCategoryEnum::VIP);

        $category5 = new TicketCategory();
        $category5->setName(TicketCategoryEnum::PEUPLE);

        $manager->persist($category);
        $manager->persist($category2);
        $manager->persist($category3);
        $manager->persist($category4);
        $manager->persist($category5);

        $manager->flush();
    }
}
