<?php

namespace App\EventSubscriber\User;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserRegisterSubscriber implements EventSubscriberInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['createUserWalletAndCart', EventPriorities::POST_VALIDATE],
        ];
    }

    public function createUserWalletAndCart(ViewEvent $event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
            //throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $wallet = new Wallet();
        $wallet->setUsers($user);
        $wallet->setAmount(0);

        $this->entityManager->persist($wallet);
        $this->entityManager->flush();

        $cart = new Cart();
        $cart->setUser($user);

        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }
}
