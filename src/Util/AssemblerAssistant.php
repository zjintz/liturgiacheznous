<?php

namespace App\Util;

use App\Entity\Book;
use App\Entity\LiturgySection;
use App\Entity\LiturgyText;
use App\Entity\Liturgy;
use App\Entity\GospelAcclamation;
use App\Repository\LiturgyRepository;

/**
 * \brief     Makes somes fixes to the LiturgyText.
 *
 *
 */
class AssemblerAssistant
{
    private $liturgyRepository;
    private $books;
    
    public function __construct( LiturgyRepository $liturgyRepository)
    {
        $this->books = $this->setUpBooks();
        $this->liturgyRepository = $liturgyRepository;
    }

    public function addDetails(LiturgyText $liturgyText)
    {
        $liturgy = $this->liturgyRepository->findOneBy(
            ['date' => $liturgyText->getDate()]
        );
        $liturgyText = $this->addBookNames($liturgyText);
        if (is_null($liturgy)) {
            $liturgyText = $this->addGospelAcclamation($liturgyText, $liturgy);
            return $liturgyText;
        }
        $description = $liturgy->getDescription();
        $description = trim($description);
        if (is_null($description) or  ($description === "")) {
            $description = $liturgy->getLiturgyDay();
        }
        $liturgyText->setDayTitle($description);
        $liturgyText = $this->addGospelAcclamation($liturgyText, $liturgy);
        return $liturgyText;
    }

    public function fixSantaInesDetails(LiturgyText $liturgyText)
    {
        if ($this->isSunday($liturgyText->getDate())) {
            $liturgyText->setSantoralSection($this->createVoidSection());
            return $liturgyText;
        }
        $liturgy = $this->liturgyRepository->findOneBy(
            ['date' => $liturgyText->getDate()]
        );
        if (is_null($liturgy)) {
            return $liturgyText;
        }
        if ($this->isSpecialDay($liturgy)) {
            $liturgyText->setTemporalSection($liturgyText->getSantoralSection());
            $liturgyText->setSantoralSection($this->createVoidSection());
            return $liturgyText;
        }
        
        return $liturgyText;
    }

    protected function isSpecialDay($liturgy)
    {
        $isSpecial = $liturgy->getIsMemorial() ||
                   $liturgy->getIsSolemnity() || $liturgy->getIsCelebration() ;
        return $isSpecial;
    }
    
    protected function isSunday(\DateTime $litDate)
    {
        $day = \date( "w", $litDate->getTimestamp());
        if($day === "0") {
            return true;
        }
        return false;
    }

    protected function createVoidSection()
    {
        $voidSection = new LiturgySection();
        $voidSection->setLoadStatus("Not_Found");
        return $voidSection;
    }

    protected function addGospelAcclamation(
        LiturgyText $liturgyText,
        ?Liturgy $liturgy
    ) {
        
        $gospelAcclamation = new GospelAcclamation();
        $gospelAcclamation->setVerse("XXXXXXX");
        $gospelAcclamation->setReference("XXXXXXX");
        if (!is_null($liturgy)) {
            if (!is_null($liturgy->getAlleluiaVerse())) {
                $gospelAcclamation->setVerse($liturgy->getAlleluiaVerse());
            }
            if (!is_null($liturgy->getAlleluiaReference())) {
                $gospelAcclamation->setReference($liturgy->getAlleluiaReference());
            }
        }
        $liturgyText->setGospelAcclamation($gospelAcclamation);
        return $liturgyText;
    }
    
    protected function addBookNames(LiturgyText $liturgyText)
    {
        $temporalSection = $liturgyText->getTemporalSection();
        $santoralSection = $liturgyText->getSantoralSection();
        if(!is_null($temporalSection)){
            $firstReading = $temporalSection->getFirstReading();
            $ref = $firstReading->getReference();
            $firstReading->setBookName($this->searchBookName($ref));
            $temporalSection->setFirstReading($firstReading);
            $secondReading = $temporalSection->getSecondReading();
            if(!is_null($secondReading)){
                $ref = $secondReading->getReference();
                $secondReading->setBookName($this->searchBookName($ref));
                $temporalSection->setSecondReading($secondReading);
                $liturgyText->setTemporalSection($temporalSection);
            }
        }
        if(!is_null($santoralSection)){
            $firstReading = $santoralSection->getFirstReading();
            if(!is_null($firstReading)){
                $ref = $firstReading->getReference();
                $firstReading->setBookName($this->searchBookName($ref));
                $santoralSection->setFirstReading($firstReading);
                $secondReading = $santoralSection->getSecondReading();
            }
            if(!is_null($secondReading)){
                $ref = $secondReading->getReference();
                $secondReading->setBookName($this->searchBookName($ref));
                $santoralSection->setSecondReading($secondReading);
                $liturgyText->setSantoralSection($santoralSection);
            }
        }
        return $liturgyText;
    }

    protected function searchBookName($reference)
    {
        $pos = strpos($reference, " ");
        $abrev = substr($reference, 0, $pos);
        foreach ($this->books as $book)
        {
            if($book->getAbbreviation() === $abrev){
                return $book->getName();
            }
        }
        return $abrev;
    }

    protected function makeBook($name, $testament , $abbreviation)
    {
        $book = new Book();
        $book->setName($name);
        $book->setTestament($testament);
        $book->setAbbreviation($abbreviation);
        return $book;       
    }
    
    protected function setUpBooks()
    {
        $books = [];
        $books[] = $this->makeBook("Gênesis", "ANTIGO TESTAMENTO", "Gn");
        $books[] = $this->makeBook("Êxodo", "ANTIGO TESTAMENTO", "Ex");
        $books[] = $this->makeBook("Levítico", "ANTIGO TESTAMENTO", "Lv");
        $books[] = $this->makeBook("Números", "ANTIGO TESTAMENTO", "Nm");
        $books[] = $this->makeBook("Deuteronômio", "ANTIGO TESTAMENTO", "Dt");
        $books[] = $this->makeBook("Josué", "ANTIGO TESTAMENTO", "Js");
        $books[] = $this->makeBook("Juízes", "ANTIGO TESTAMENTO", "Jz");
        $books[] = $this->makeBook("Rute", "ANTIGO TESTAMENTO", "Rt");
        $books[] = $this->makeBook("I Samuel", "ANTIGO TESTAMENTO", "I Sm");
        $books[] = $this->makeBook("II Samuel", "ANTIGO TESTAMENTO", "II Sm");
        $books[] = $this->makeBook("I Reis", "ANTIGO TESTAMENTO", "I Re");
        $books[] = $this->makeBook("II Reis", "ANTIGO TESTAMENTO", "II Re");
        $books[] = $this->makeBook("I Crônicas", "ANTIGO TESTAMENTO", "I Cr");
        $books[] = $this->makeBook("II Crônicas", "ANTIGO TESTAMENTO", "II Cr");
        $books[] = $this->makeBook("Esdras", "ANTIGO TESTAMENTO", "Ed");
        $books[] = $this->makeBook("Neemias", "ANTIGO TESTAMENTO", "Ne");
        $books[] = $this->makeBook("Ester", "ANTIGO TESTAMENTO", "Et");
        $books[] = $this->makeBook("Jó", "ANTIGO TESTAMENTO", "Jó");
        $books[] = $this->makeBook("Salmos", "ANTIGO TESTAMENTO", "Sl");
        $books[] = $this->makeBook("Provérbios", "ANTIGO TESTAMENTO", "Pv");
        $books[] = $this->makeBook("Eclesiastes", "ANTIGO TESTAMENTO", "Ec");
        $books[] = $this->makeBook("Cantares", "ANTIGO TESTAMENTO", "Ct");
        $books[] = $this->makeBook("Isaías", "ANTIGO TESTAMENTO", "Is");
        $books[] = $this->makeBook("Jeremias", "ANTIGO TESTAMENTO", "Jr");
        $books[] = $this->makeBook("Lamentações", "ANTIGO TESTAMENTO", "Lm");
        $books[] = $this->makeBook("Ezequiel", "ANTIGO TESTAMENTO", "Ez");
        $books[] = $this->makeBook("Daniel", "ANTIGO TESTAMENTO", "Dn");
        $books[] = $this->makeBook("Oseias", "ANTIGO TESTAMENTO", "Os");
        $books[] = $this->makeBook("Joel", "ANTIGO TESTAMENTO", "Jl");
        $books[] = $this->makeBook("Amós", "ANTIGO TESTAMENTO", "Am");
        $books[] = $this->makeBook("Obadias", "ANTIGO TESTAMENTO", "Ob");
        $books[] = $this->makeBook("Jonas", "ANTIGO TESTAMENTO", "Jn");
        $books[] = $this->makeBook("Miqueias", "ANTIGO TESTAMENTO", "Mq");
        $books[] = $this->makeBook("Naum", "ANTIGO TESTAMENTO", "Na");
        $books[] = $this->makeBook("Habacuque", "ANTIGO TESTAMENTO", "Hc");
        $books[] = $this->makeBook("Sofonias", "ANTIGO TESTAMENTO", "Sf");
        $books[] = $this->makeBook("Ageu", "ANTIGO TESTAMENTO", "Ag");
        $books[] = $this->makeBook("Zacarias", "ANTIGO TESTAMENTO", "Zc");
        $books[] = $this->makeBook("Malaquias", "ANTIGO TESTAMENTO", "Ml");
        $books[] = $this->makeBook("Mateus", "NOVO TESTAMENTO", "Mt");
        $books[] = $this->makeBook("Maços", "NOVO TESTAMENTO", "Mc");
        $books[] = $this->makeBook("Lucas", "NOVO TESTAMENTO", "Lc");
        $books[] = $this->makeBook("João", "NOVO TESTAMENTO", "Jo");
        $books[] = $this->makeBook("Atos", "NOVO TESTAMENTO", "At");
        $books[] = $this->makeBook("Romanos", "NOVO TESTAMENTO", "Rm");
        $books[] = $this->makeBook("I Coríntios", "NOVO TESTAMENTO", "ICo");
        $books[] = $this->makeBook("II Coríntios", "NOVO TESTAMENTO", "IICo");
        $books[] = $this->makeBook("Gálatas", "NOVO TESTAMENTO", "Gl");
        $books[] = $this->makeBook("Efésios", "NOVO TESTAMENTO", "Ef");
        $books[] = $this->makeBook("Filipenses", "NOVO TESTAMENTO", "Fp");
        $books[] = $this->makeBook("Colossenses", "NOVO TESTAMENTO", "Cl");
        $books[] = $this->makeBook("I Tessalonicenses", "NOVO TESTAMENTO", "ITs");
        $books[] = $this->makeBook("II Tessalonicenses", "NOVO TESTAMENTO", "IITs");
        $books[] = $this->makeBook("I Timóteo", "NOVO TESTAMENTO", "ITm");
        $books[] = $this->makeBook("II Timóteo", "NOVO TESTAMENTO", "IITm");
        $books[] = $this->makeBook("Tito", "NOVO TESTAMENTO", "Tt");
        $books[] = $this->makeBook("Filemom", "NOVO TESTAMENTO", "Fm");
        $books[] = $this->makeBook("Hebreus", "NOVO TESTAMENTO", "Hb");
        $books[] = $this->makeBook("Tiago", "NOVO TESTAMENTO", "Tg");
        $books[] = $this->makeBook("I Pedro", "NOVO TESTAMENTO", "IPe");
        $books[] = $this->makeBook("II Pedro", "NOVO TESTAMENTO", "IIPe");
        $books[] = $this->makeBook("I João", "NOVO TESTAMENTO", "IJo");
        $books[] = $this->makeBook("II João", "NOVO TESTAMENTO", "IIJo");
        $books[] = $this->makeBook("III João", "NOVO TESTAMENTO", "IIIJo");
        $books[] = $this->makeBook("Judas", "NOVO TESTAMENTO", "Jd");
        $books[] = $this->makeBook("Apocalipse", "NOVO TESTAMENTO", "Ap");
        return $books;
    }

}
