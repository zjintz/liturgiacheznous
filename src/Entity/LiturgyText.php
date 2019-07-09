<?php

namespace App\Entity;

/**
 * \brief Contains all the parts of the liturgy text.
 *
 */
class LiturgyText
{
    private $dayTitle;
    private $temporalSection;
    private $santoralSection;
    private $loadStatus;
    private $date;


    public function getDayTitle(): ?string
    {
        return $this->dayTitle;
    }

    public function setDayTitle(string $dayTitle): self
    {
        $this->dayTitle = $dayTitle;

        return $this;
    }
    
    public function getLoadStatus(): ?string
    {
        return $this->loadStatus;
    }

    public function setLoadStatus(string $loadStatus): self
    {
        $this->loadStatus = $loadStatus;

        return $this;
    }
    public function getTemporalSection(): ?LiturgySection
    {
        return $this->temporalSection;
    }

    public function setTemporalSection(LiturgySection $section): self
    {
        $this->temporalSection = $section;

        return $this;
    }

    public function getSantoralSection(): ?LiturgySection
    {
        return $this->santoralSection;
    }

    public function setSantoralSection(LiturgySection $section): self
    {
        $this->santoralSection = $section;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date): self
    {
        $this->date = $date;
        return $this;
    }    
    
}
