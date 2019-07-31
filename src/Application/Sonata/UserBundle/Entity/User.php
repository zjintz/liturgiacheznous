<?php

namespace App\Application\Sonata\UserBundle\Entity;

use App\Entity\Headquarter;
use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This file has been generated by the SonataEasyExtendsBundle.
 *
 * @link https://sonata-project.org/easy-extends
 *
 * References:
 * @link http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 */
class User extends BaseUser
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Headquarter", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Type(type="App\Entity\Headquarter")
     * @Assert\Valid
     */
    private $headquarter;

    
    /**
     * @var int $id
     */
    protected $id;

    /**
     * Get id.
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    public function getHeadquarter(): ?Headquarter
    {
        return $this->headquarter;
    }

    public function setHeadquarter(?Headquarter $headquarter): self
    {
        $this->headquarter = $headquarter;

        return $this;
    }

     /**
     * Overridden so that username is now optional
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->setUsername($email);
        return parent::setEmail($email);
    }
}