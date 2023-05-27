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
    public function __construct(
        private readonly Security $security,
        private readonly UserRepository $userRepository
    ){}

    public function __invoke(): User
    {
        return $this->userRepository->find($this->security->getUser()->getId());
    }
}
