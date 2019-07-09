<?php

namespace App\Entity;

/**
 * \brief Contains all the parts of a psalm reading.
 *
 */
class PsalmReading implements Reading
{
    private $chorus;
    private $title;
    private $text;
    private $reference; 
    
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
    
    public function getChorus(): ?string
    {
        return $this->chorus;
    }

    public function setChorus(string $chorus): self
    {
        $this->chorus = $chorus;

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
