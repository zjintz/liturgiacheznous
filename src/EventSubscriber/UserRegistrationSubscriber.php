<?php

namespace App\EventSubscriber;

use App\Entity\EmailSubscription;
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
        $subscription = new EmailSubscription();
        $subscription->setIsActive(false);
        $subscription->setPeriodicity('1');
        $subscription->setDaysAhead(1);
        $user = $event->getUser();
        /** @var \AppBundle\Entity\User $user */
        $user->setEnabled(false);
        $user->setEmailSubscription($subscription);
        // ...
    }

    public function registrationFlashMessage(FormEvent $event)
    {
        $url = $this->router->generate('fos_user_security_login');
        $response = new RedirectResponse($url);
        
        $this->session->getFlashBag()->add(
            'regg-success',
            'user_registration.success_message'
        );
        $this->session->getFlashBag()->add(
            'regg-success',
            'user_registration.activation_notice'
        );
        $event->setResponse($response);
    }

}
