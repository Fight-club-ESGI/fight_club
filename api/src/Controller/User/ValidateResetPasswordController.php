<?php

namespace App\Controller\User;

use App\Entity\User;
use Carbon\Carbon;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
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
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function __invoke(): Response
    {
        // TODO : Secure if not email in body

        $token = json_decode($this->requestStack->getCurrentRequest()->getContent())->token;
        $password = json_decode($this->requestStack->getCurrentRequest()->getContent())->password;

        /** @var User $user */
        $user = $this->managerRegistry->getRepository(User::class)->findOneBy(['token' => $token]);

        if (!$user) {
            throw $this->createNotFoundException();
        }

        if (!$user->getToken() || !$user->getTokenDate()) {
            throw new BadRequestException("Link validity has expired", 400);
        }

        $tokenDateValidity = new Carbon($user->getTokenDate());
        $tokenDateValidity->addMinutes(30);
        $tokenValidity = $tokenDateValidity->greaterThan(Carbon::now());

        if (!$tokenValidity) {
            throw new BadRequestException("Token validity has expired, please try again", 400);
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password
        );

        $user->setPassword($hashedPassword);
        $user->setToken(null);
        $user->setTokenDate(null);
        $this->managerRegistry->getManager()->flush();

        return new Response("Password successfully modified", 200);
    }
}
