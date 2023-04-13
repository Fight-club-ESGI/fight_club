<?php

namespace App\Controller\User;

use Carbon\Carbon;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class CheckTokenValidityController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ManagerRegistry $managerRegistry,
        private readonly MailerInterface $mailer,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function __invoke()
    {
        // TODO : Secure if not email in body
        $token = json_decode($this->requestStack->getCurrentRequest()->getContent())->token;

        /** @var User $user */
        if (!$user = $this->managerRegistry->getRepository(User::class)->findOneBy(['token' => $token])) {
            throw $this->createNotFoundException();
        }

        $tokenDateValidity = new Carbon($user->getTokenDate());
        $tokenDateValidity->addSecondes(10);
        $tokenValidity = $tokenDateValidity->greaterThan(Carbon::now());

        if ($tokenValidity) {
            return new Response("success", 200);
        }

        return new \Exception("Token validity has expired, please try again", 400);
    }
}
