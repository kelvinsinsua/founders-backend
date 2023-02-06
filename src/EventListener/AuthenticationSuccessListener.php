<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;

class AuthenticationSuccessListener {
    
    protected $em;
    private $requestStack;

    function __construct(EntityManagerInterface $em,RequestStack $requestStack) {
        $this->em = $em;
        $this->requestStack = $requestStack;
    }
    
    
   public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
{
    $data = $event->getData();
    $user = $event->getUser();

    if (!$user instanceof UserInterface) {
        return;
    }
    $data['email'] = $user->getEmail();
    $data['roles'] = $user->getRoles();
    $data['id'] = $user->getId();
    $event->setData($data);
}

}
