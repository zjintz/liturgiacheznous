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
                 $message = (new \Swift_Message('Sua conta foi ativada'))
                          ->setFrom('no_reply@liturgiacheznous.org')
                          ->setTo($user->getEmail())
                          ->setBody(
                              $this->renderView(
                                  // templates/emails/registration.html.twig
                                  'emails/account_enabled.html.twig',
                                  ['name' => $user->getName()]
                              ),
                              'text/html'
                          );

                 $this->mailer->send($message);
            }
        }
    }
}
