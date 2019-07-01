<?php

namespace App\Entity;

/**
 * \brief Contains all the parts of the liturgy text.
 *
 */
class LiturgyText
{
    private $temporalSection;

    private $santoralSection;

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
}
