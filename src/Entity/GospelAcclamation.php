<?php

namespace App\Entity;

/**
 * \brief Gospel Acclamation.
 *
 */
class GospelAcclamation
{
    private $verse;
    private $reference;
    
    public function getVerse(): ?string
    {
        return $this->verse;
    }

    public function setVerse(string $verse): self
    {
        $this->verse = $verse;

        return $this;
    }
    
    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }
        
}
