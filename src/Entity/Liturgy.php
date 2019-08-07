<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LiturgyRepository")
 */
class Liturgy
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $liturgyDay;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $color;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSolemnity;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSolemnityVFC;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isCelebration;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isCelebrationVFC;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isMemorial;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isMemorialVFC;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isMemorialFree;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $yearType;

    /**
     * @ORM\Column(type="text")
     */
    private $summary;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alleluiaVerse;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $alleluiaReference;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLiturgyDay(): ?string
    {
        return $this->liturgyDay;
    }

    public function setLiturgyDay(string $liturgyDay): self
    {
        $this->liturgyDay = $liturgyDay;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getIsSolemnity(): ?bool
    {
        return $this->isSolemnity;
    }

    public function setIsSolemnity(?bool $isSolemnity): self
    {
        $this->isSolemnity = $isSolemnity;

        return $this;
    }

    public function getIsSolemnityVFC(): ?bool
    {
        return $this->isSolemnityVFC;
    }

    public function setIsSolemnityVFC(?bool $isSolemnityVFC): self
    {
        $this->isSolemnityVFC = $isSolemnityVFC;

        return $this;
    }

    public function getIsCelebration(): ?bool
    {
        return $this->isCelebration;
    }

    public function setIsCelebration(?bool $isCelebration): self
    {
        $this->isCelebration = $isCelebration;

        return $this;
    }

    public function getIsCelebrationVFC(): ?bool
    {
        return $this->isCelebrationVFC;
    }

    public function setIsCelebrationVFC(?bool $isCelebrationVFC): self
    {
        $this->isCelebrationVFC = $isCelebrationVFC;

        return $this;
    }

    public function getIsMemorial(): ?bool
    {
        return $this->isMemorial;
    }

    public function setIsMemorial(?bool $isMemorial): self
    {
        $this->isMemorial = $isMemorial;

        return $this;
    }

    public function getIsMemorialVFC(): ?bool
    {
        return $this->isMemorialVFC;
    }

    public function setIsMemorialVFC(?bool $isMemorialVFC): self
    {
        $this->isMemorialVFC = $isMemorialVFC;

        return $this;
    }

    public function getIsMemorialFree(): ?bool
    {
        return $this->isMemorialFree;
    }

    public function setIsMemorialFree(?bool $isMemorialFree): self
    {
        $this->isMemorialFree = $isMemorialFree;

        return $this;
    }

    public function getYearType(): ?string
    {
        return $this->yearType;
    }

    public function setYearType(string $yearType): self
    {
        $this->yearType = $yearType;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getAlleluiaVerse(): ?string
    {
        return $this->alleluiaVerse;
    }

    public function setAlleluiaVerse(?string $alleluiaVerse): self
    {
        $this->alleluiaVerse = $alleluiaVerse;

        return $this;
    }

    public function getAlleluiaReference(): ?string
    {
        return $this->alleluiaReference;
    }

    public function setAlleluiaReference(?string $alleluiaReference): self
    {
        $this->alleluiaReference = $alleluiaReference;

        return $this;
    }
}
