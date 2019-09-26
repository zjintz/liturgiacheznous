<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Application\Sonata\UserBundle\Entity\User;

class UserNotEnabledTestFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $testUser = new User();
        $testUser->setUsername('userne@test.com');
        $testUser->setPlainPassword('testnePass');
        $testUser->setEnabled(FALSE);
        $testUser->setEmail('userne@test.com');
        $manager->persist($testUser);
        $manager->flush();
    }
}
