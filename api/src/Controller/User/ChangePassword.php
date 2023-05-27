<?php

namespace App\Controller\User;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class ChangePassword extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ManagerRegistry $managerRegistry,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly Security $security
    ) {}

    public function __invoke(): Response
    {
        $newPassword = json_decode($this->requestStack->getCurrentRequest()->getContent())->newPassword;
        $password = json_decode($this->requestStack->getCurrentRequest()->getContent())->password;

        /** @var User $user */
        if (!$user = $this->managerRegistry->getRepository(User::class)->findOneBy(['id' => $this->security->getUser()->getId()])) {
            throw $this->createNotFoundException();
        }

        // Check that password equals old password
        if (!password_verify($password, $user->getPassword())) {
            throw new BadRequestException("Error while updating password, please try again", 400);
        }

        $newlyHashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $newPassword
        );

        $user->setPassword($newlyHashedPassword);
        $this->managerRegistry->getManager()->flush();

        return new Response("Password successfully modified", 200);
    }
}
