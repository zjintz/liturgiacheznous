<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmailSubscriptionRepository")
 */
class EmailSubscription
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $periodicity;

    /**
     * @ORM\Column(type="smallint")
     */
    private $daysAhead;

    /**
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", mappedBy="emailSubscription", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $format = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $source = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getPeriodicity(): ?string
    {
        return $this->periodicity;
    }

    public function setPeriodicity(?string $periodicity): self
    {
        $this->periodicity = $periodicity;

        return $this;
    }

    public function getDaysAhead(): ?int
    {
        return $this->daysAhead;
    }

    public function setDaysAhead(int $daysAhead): self
    {
        $this->daysAhead = $daysAhead;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
                // set the owning side of the relation if necessary
        if ($this !== $user->getEmailSubscription()) {
            $user->setEmailSubscription($this);
        }


        return $this;
    }

    public function getFormat(): ?array
    {
        return $this->format;
    }

    public function setFormat(array $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getSource(): ?array
    {
        return $this->source;
    }

    public function setSource(?array $source): self
    {
        $this->source = $source;

        return $this;
    }
}
