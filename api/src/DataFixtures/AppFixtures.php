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
        foreach (TicketCategoryEnum::cases() as $categoryName) {
            $category = new TicketCategory();
            $category->setName($categoryName);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
