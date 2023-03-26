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

#[AsController]
class ResetPasswordController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ManagerRegistry $managerRegistry,
        private readonly MailerInterface $mailer
    ) {}

    public function __invoke(): JsonResponse
    {
        // TODO : Secure if not email in body
        $email = json_decode($this->requestStack->getCurrentRequest()->getContent())->email;

        /** @var User $user */
        if (!$user = $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $email])) {
            throw $this->createNotFoundException();
        }

        $token = bin2hex(random_bytes(32));

        $user->setToken($token);
        $this->managerRegistry->getManager()->flush();

        // TODO : send email
        $email = (new Email())
            ->from($_ENV['MAILER_FROM'])
            ->to($user->getEmail())
            ->subject('Password reset')
            ->html('<p>Hi ' . $user->getEmail() . ',</p>
            <p>Click on the link below to reset your password.</p>
            <p><a href="http://localhost:8080/users/validate/password' . $token . '">Reset password</a></p>
            <p>Thanks,</p>
            <p>Thunderous Knockout Fighting</p>');

        $this->mailer->send($email);

        return $this->json('Success');
    }
}
