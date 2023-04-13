<?php

namespace App\Controller\User;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class ValidateResetPasswordController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ManagerRegistry $managerRegistry,
        private readonly MailerInterface $mailer,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function __invoke(): JsonResponse
    {
        // TODO : Secure if not email in body
        $token = json_decode($this->requestStack->getCurrentRequest()->getContent())->token;
        $password = json_decode($this->requestStack->getCurrentRequest()->getContent())->password;

        /** @var User $user */
        if (!$user = $this->managerRegistry->getRepository(User::class)->findOneBy(['token' => $token])) {
            throw $this->createNotFoundException();
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );

        $user->setPassword($hashedPassword);
        $user->setToken(null);
        $user->setTokenDate(null);
        $this->managerRegistry->getManager()->flush();

        // Change user password

        return $this->json('Success');
    }
}
