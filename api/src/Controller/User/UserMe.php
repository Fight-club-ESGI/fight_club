<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\SecurityBundle\Security;

#[AsController]
class UserMe extends AbstractController
{
    public function __invoke(Security $security, UserRepository $userRepository): User
    {
        return $userRepository->find($security->getUser()->getId());
    }
}
