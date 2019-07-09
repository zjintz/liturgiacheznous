<?php

namespace App\Entity;

/**
 * \brief A interface that defines common methods of the diferent readings of a
 * liturgy.
 *
 */
interface Reading
{
    public function getTitle(): ?string;
    public function setTitle(string $title);
    public function getText(): ?string;
    public function setText(string $text);
    public function getReference(): ?string;
    public function setReference(string $reference);
    
}
