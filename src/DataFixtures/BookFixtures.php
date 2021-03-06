<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;


class BookFixtures extends Fixture implements FixtureGroupInterface
{
    
    public function load(ObjectManager $manager)
    {
        $books = [];
        $books[] = $this->makeBook("Gênesis", "ANTIGO TESTAMENTO", "Gn.");
        $books[] = $this->makeBook("Êxodo", "ANTIGO TESTAMENTO", "Êx.");
        $books[] = $this->makeBook("Levítico", "ANTIGO TESTAMENTO", "Lv.");
        $books[] = $this->makeBook("Números", "ANTIGO TESTAMENTO", "Nm.");
        $books[] = $this->makeBook("Deuteronômio", "ANTIGO TESTAMENTO", "Dt.");
        $books[] = $this->makeBook("Josué", "ANTIGO TESTAMENTO", "Js.");
        $books[] = $this->makeBook("Juízes", "ANTIGO TESTAMENTO", "Jz.");
        $books[] = $this->makeBook("Rute", "ANTIGO TESTAMENTO", "Rt.");
        $books[] = $this->makeBook("I Samuel", "ANTIGO TESTAMENTO", "I Sm.");
        $books[] = $this->makeBook("II Samuel", "ANTIGO TESTAMENTO", "II Sm.");
        $books[] = $this->makeBook("I Reis", "ANTIGO TESTAMENTO", "I Re.");
        $books[] = $this->makeBook("II Reis", "ANTIGO TESTAMENTO", "II Re.");
        $books[] = $this->makeBook("I Crônicas", "ANTIGO TESTAMENTO", "I Cr.");
        $books[] = $this->makeBook("II Crônicas", "ANTIGO TESTAMENTO", "II Cr.");
        $books[] = $this->makeBook("Esdras", "ANTIGO TESTAMENTO", "Ed.");
        $books[] = $this->makeBook("Neemias", "ANTIGO TESTAMENTO", "Ne.");
        $books[] = $this->makeBook("Ester", "ANTIGO TESTAMENTO", "Et.");
        $books[] = $this->makeBook("Jó", "ANTIGO TESTAMENTO", "Jó");
        $books[] = $this->makeBook("Salmos", "ANTIGO TESTAMENTO", "Sl.");
        $books[] = $this->makeBook("Provérbios", "ANTIGO TESTAMENTO", "Pv.");
        $books[] = $this->makeBook("Eclesiastes", "ANTIGO TESTAMENTO", "Ec.");
        $books[] = $this->makeBook("Cantares", "ANTIGO TESTAMENTO", "Ct.");
        $books[] = $this->makeBook("Isaías", "ANTIGO TESTAMENTO", "Is.");
        $books[] = $this->makeBook("Jeremias", "ANTIGO TESTAMENTO", "Jr.");
        $books[] = $this->makeBook("Lamentações", "ANTIGO TESTAMENTO", "Lm.");
        $books[] = $this->makeBook("Ezequiel", "ANTIGO TESTAMENTO", "Ez.");
        $books[] = $this->makeBook("Daniel", "ANTIGO TESTAMENTO", "Dn.");
        $books[] = $this->makeBook("Oseias", "ANTIGO TESTAMENTO", "Os.");
        $books[] = $this->makeBook("Joel", "ANTIGO TESTAMENTO", "Jl.");
        $books[] = $this->makeBook("Amós", "ANTIGO TESTAMENTO", "Am.");
        $books[] = $this->makeBook("Obadias", "ANTIGO TESTAMENTO", "Ob.");
        $books[] = $this->makeBook("Jonas", "ANTIGO TESTAMENTO", "Jn.");
        $books[] = $this->makeBook("Miqueias", "ANTIGO TESTAMENTO", "Mq.");
        $books[] = $this->makeBook("Naum", "ANTIGO TESTAMENTO", "Na.");
        $books[] = $this->makeBook("Habacuque", "ANTIGO TESTAMENTO", "Hc.");
        $books[] = $this->makeBook("Sofonias", "ANTIGO TESTAMENTO", "Sf.");
        $books[] = $this->makeBook("Ageu", "ANTIGO TESTAMENTO", "Ag.");
        $books[] = $this->makeBook("Zacarias", "ANTIGO TESTAMENTO", "Zc.");
        $books[] = $this->makeBook("Malaquias", "ANTIGO TESTAMENTO", "Ml.");
        $books[] = $this->makeBook("Mateus", "NOVO TESTAMENTO", "Mt.");
        $books[] = $this->makeBook("Maços", "NOVO TESTAMENTO", "Mc.");
        $books[] = $this->makeBook("Lucas", "NOVO TESTAMENTO", "Lc.");
        $books[] = $this->makeBook("João", "NOVO TESTAMENTO", "Jo.");
        $books[] = $this->makeBook("Atos", "NOVO TESTAMENTO", "At.");
        $books[] = $this->makeBook("Romanos", "NOVO TESTAMENTO", "Rm.");
        $books[] = $this->makeBook("I Coríntios", "NOVO TESTAMENTO", "I Co.");
        $books[] = $this->makeBook("II Coríntios", "NOVO TESTAMENTO", "II Co.");
        $books[] = $this->makeBook("Gálatas", "NOVO TESTAMENTO", "Gl.");
        $books[] = $this->makeBook("Efésios", "NOVO TESTAMENTO", "Ef.");
        $books[] = $this->makeBook("Filipenses", "NOVO TESTAMENTO", "Fp.");
        $books[] = $this->makeBook("Colossenses", "NOVO TESTAMENTO", "Cl.");
        $books[] = $this->makeBook("I Tessalonicenses", "NOVO TESTAMENTO", "I Ts.");
        $books[] = $this->makeBook("II Tessalonicenses", "NOVO TESTAMENTO", "II Ts.");
        $books[] = $this->makeBook("I Timóteo", "NOVO TESTAMENTO", "I Tm.");
        $books[] = $this->makeBook("II Timóteo", "NOVO TESTAMENTO", "II Tm.");
        $books[] = $this->makeBook("Tito", "NOVO TESTAMENTO", "Tt.");
        $books[] = $this->makeBook("Filemom", "NOVO TESTAMENTO", "Fm.");
        $books[] = $this->makeBook("Hebreus", "NOVO TESTAMENTO", "Hb.");
        $books[] = $this->makeBook("Tiago", "NOVO TESTAMENTO", "Tg.");
        $books[] = $this->makeBook("I Pedro", "NOVO TESTAMENTO", "I Pe.");
        $books[] = $this->makeBook("II Pedro", "NOVO TESTAMENTO", "II Pe.");
        $books[] = $this->makeBook("I João", "NOVO TESTAMENTO", "I Jo.");
        $books[] = $this->makeBook("II João", "NOVO TESTAMENTO", "II Jo.");
        $books[] = $this->makeBook("III João", "NOVO TESTAMENTO", "III Jo.");
        $books[] = $this->makeBook("Judas", "NOVO TESTAMENTO", "Jd.");
        $books[] = $this->makeBook("Apocalipse", "NOVO TESTAMENTO", "Ap.");
        foreach($books as $book) {
            $manager->persist($book);
        }
        $manager->flush();
    }

    protected function makeBook($name, $testament , $abbreviation)
    {
        $book = new Book();
        $book->setName($name);
        $book->setTestament($testament);
        $book->setAbbreviation($abbreviation);
        return $book;       
    }

    public static function getGroups(): array
    {
        return ['books'];
    }
}
