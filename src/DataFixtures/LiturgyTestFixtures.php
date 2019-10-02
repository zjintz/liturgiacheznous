<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Liturgy;

class LiturgyTestFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $liturgy = new Liturgy();
        $liturgy->setDate(new \DateTime());
            $liturgy->setLiturgyDay("Dia 1");
            $liturgy->setDescription("Description");
            $liturgy->setColor("Dourado");
            $liturgy->setIsSolemnity(false);
            $liturgy->setIsSolemnityVFC(false);
            $liturgy->setIsCelebration(false);
            $liturgy->setIsCelebrationVFC(false);
            $liturgy->setIsMemorial(false);
            $liturgy->setIsMemorialVFC(false);
            $liturgy->setIsMemorialFree(false);
            $liturgy->setYearType("c");
            $liturgy->setAlleluiaReference("ref");
            $liturgy->setAlleluiaVerse("verse");
            $liturgy->setSummary("sumary");
            $manager->persist($liturgy);
        $manager->flush();
    }
}
