<?php

namespace App\EventSubscriber;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;



class UserRegistrationSubscriber implements EventSubscriberInterface
{
    private $router;
    private $session;
    
    public function __construct(RouterInterface $router, SessionInterface $session)
    {
        $this->router = $router;
        $this->session = $session;
    }
    
    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
            FOSUserEvents::REGISTRATION_INITIALIZE => [
                ['disableUser', 0],
            ],
            FOSUserEvents::REGISTRATION_SUCCESS => [
                ['registrationFlashMessage', 0],
            ],
        ];
    }

    public function disableUser(GetResponseUserEvent $event)
    {
        $user = $event->getUser();
        /** @var \AppBundle\Entity\User $user */
        $user->setEnabled(false);
        // ...
    }

    public function registrationFlashMessage(FormEvent $event)
    {
        $url = $this->router->generate('fos_user_security_login');
        $response = new RedirectResponse($url);
        
        $this->session->getFlashBag()->add(
            'success',
            'You will receive a notification e-mail after an Admin of Liturgiacheznous activates your account.'
        );
        $event->setResponse($response);
    }

}