<?php

// src/EventListener/UserChangedNotifier.php
namespace App\EventListener;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

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
                 $message = (new TemplatedEmail('Sua conta foi ativada'))
                          ->setFrom('no_reply@liturgiacheznous.org')
                          ->setTo($user->getEmail())
                          ->htmlTemplate('emails/account_enabled.html.twig')
                          ->context(['name'=>$user->getFirstname()]);

                 $this->mailer->send($message);
            }
        }
    }
}
