<?php

namespace App\Entity;

/**
 * \brief Contains all the readings of a liturgy section.
 *
 */
class LiturgySection
{
    
    private $firstReading;
    private $psalmReading;
    private $secondReading;
    private $gospelReading;

    public function getFirstReading(): ?LiturgyReading
    {
        return $this->firstReading;
    }

    public function setFirstReading(LiturgyReading $firstReading): self
    {
        $this->firstReading = $firstReading;

        return $this;
    }

    public function getPsalmReading(): ?PsalmReading
    {
        return $this->psalmReading;
    }

    public function setPsalmReading(Psalm $psalmReading): self
    {
        $this->psalmReading = $psalmReading;

        return $this;
    }

    public function getSecondReading(): ?LiturgyReading
    {
        return $this->secondReading;
    }

    public function setSecondReading(LiturgyReading $secondReading): self
    {
        $this->secondReading = $secondReading;

        return $this;
    }

    public function getGospelReading(): ?LiturgyReading
    {
        return $this->gospelReading;
    }

    public function setGospelReading(LiturgyReading $gospelReading): self
    {
        $this->gospelReading = $gospelReading;

        return $this;
    }        
}
