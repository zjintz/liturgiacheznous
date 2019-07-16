<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Headquarter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Creates the a test user , to run the functional tests.
 *
 *
 */
class UserTestFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->encoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $headquarter = new Headquarter();
        $headquarter->setName("testHQ");
        $headquarter->setCity("testCity");
        $headquarter->setCountry("testCountry");
        $user = new User();
        $user->setName("tester");
        $password = $this->encoder->encodePassword($user, 'testpass');
        $user->setPassword($password);
        $user->setEmail("tester@test.com");
        $manager->persist($headquarter);
        $user->setHeadquarter($headquarter);
        $manager->persist($user);
        $manager->flush();
    }
}
