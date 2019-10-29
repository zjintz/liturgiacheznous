<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TextsDeliveryRepository")
 */
class TextsDelivery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sendDate;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSendDate(): ?\DateTimeInterface
    {
        return $this->sendDate;
    }

    public function setSendDate(\DateTimeInterface $sendDate): self
    {
        $this->sendDate = $sendDate;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
