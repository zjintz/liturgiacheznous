<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Application\Sonata\UserBundle\Entity\User;
use App\Entity\Headquarter;
use App\Entity\EmailSubscription;

class UserTestActiveSubsFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        
        $testHQ = new Headquarter();
        $testHQ->setCity("testCity");
        $testHQ->setCountry("testCountry");
        $testHQ->setName("testHQ");
        $testUser = new User();
        $testUser->setUsername('user@test.com');
        $testUser->setPlainPassword('testPass');
        $testUser->setEnabled(true);
        $testUser->setEmail('user@test.com');
        $testUser->setRoles(["ROLE_USER"]);
        $testUser->setHeadquarter($testHQ);
        $testEditor = new User();
        $testEditor->setUsername('editor@test.com');
        $testEditor->setPlainPassword('testPass');
        $testEditor->setEnabled(true);
        $testEditor->setEmail('editor@test.com');
        $testEditor->setRoles(["ROLE_EDITOR"]);
        $testEditor->setHeadquarter($testHQ);
        $testEditor->setEmailSubscription($this->createSubscription($manager));
        $manager->persist($testUser);
        $manager->persist($testEditor);
        $manager->flush();
    }
    
    protected function createSubscription(ObjectManager $manager)
    {
        $activeSubs = new EmailSubscription();
        $activeSubs->setIsActive(true);
        $activeSubs->setPeriodicity("1");
        $activeSubs->setDaysAhead(1);
        $manager->persist($activeSubs);
        return $activeSubs;
    }
}
