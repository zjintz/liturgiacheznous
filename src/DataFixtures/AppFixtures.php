<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Liturgy;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $parmeterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag= $parameterBag;
    }
    
    public function load(ObjectManager $manager)
    {
        $rootDir = $this->parameterBag->get('kernel.project_dir');
        $csv = fopen($rootDir.'/data/data-Liturgia.csv', 'r');

        $num = 0;
        $line = fgetcsv($csv);
        while (!feof($csv)) {


            $liturgy[$num] = new Liturgy();
            $liturgy[$num]->setDate(new \DateTime($line[0]));
            $liturgy[$num]->setLiturgyDay($line[2]);
            $liturgy[$num]->setDescription($line[3]);
            $liturgy[$num]->setColor($line[4]);
            $liturgy[$num]->setIsSolemnity(FALSE);
            if($line[5]==='X'){
                $liturgy[$num]->setIsSolemnity(TRUE);
            }
            $liturgy[$num]->setIsSolemnityVFC(FALSE);
            if($line[6]==='X'){
                $liturgy[$num]->setIsSolemnityVFC(TRUE);
            }
            $liturgy[$num]->setIsCelebration(FALSE);
            if($line[7]==='X'){
                $liturgy[$num]->setIsCelebration(TRUE);
            }
            $liturgy[$num]->setIsCelebrationVFC(FALSE);
            if($line[8]==='X'){
                $liturgy[$num]->setIsCelebrationVFC(TRUE);
            }
            $liturgy[$num]->setIsMemorial(FALSE);
            if($line[9]==='X'){
                $liturgy[$num]->setIsMemorial(TRUE);
            }
            $liturgy[$num]->setIsMemorialVFC(FALSE);
            if($line[10]==='X'){
                $liturgy[$num]->setIsMemorialVFC(TRUE);
            }
            $liturgy[$num]->setIsMemorialFree(FALSE);
            if($line[11]==='X'){
                $liturgy[$num]->setIsMemorialFree(TRUE);
            }
            $liturgy[$num]->setYearType($line[12]);
            $liturgy[$num]->setAlleluiaReference($line[13]);
            $liturgy[$num]->setAlleluiaVerse($line[14]);
            $liturgy[$num]->setSummary($line[15]);
            $manager->persist($liturgy[$num]);
            $num += 1;
            $line = fgetcsv($csv);
        }
        fclose($csv);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['app'];
    }
}
