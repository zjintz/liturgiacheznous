<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Application\Sonata\UserBundle\Entity\User;

class UserTestFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $testUser = new User();
        $testUser->setUsername('user@test.com');
        $testUser->setPlainPassword('testPass');
        $testUser->setEnabled(TRUE);
        $testUser->setEmail('user@test.com');
        $manager->persist($testUser);
        $manager->flush();
    }
}
