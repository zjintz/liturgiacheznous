<?php

namespace App\Entity;

/**
 * \brief Contains all the parts of a psalm reading.
 *
 */
class PsalmReading
{
    
    private $title;
    private $chorus;
    private $text;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
        
}
