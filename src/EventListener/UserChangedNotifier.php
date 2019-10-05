<?php

// src/EventListener/UserChangedNotifier.php
namespace App\EventListener;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Event\PreUpdateEventArgs;


class UserChangedNotifier
{
    private $mailer;
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function preUpdate(User $user, PreUpdateEventArgs $event)
    {
        if ($event->hasChangedField('enabled')) {
            if ($event->getNewValue('enabled')) {
                 $message = (new \Swift_Message('Su cuenta fue Activada'))
                          ->setFrom('liturgiacheznous@gmail.com')
                          ->setTo($user->getEmail())
                          ->addPart(
                              'Su cuenta fue activada con exito, ya puedes entrar a liturgiacheznous.org '
                          );
                 $this->mailer->send($message);
            }
        }
    }
}
