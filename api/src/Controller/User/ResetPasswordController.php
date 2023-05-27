<?php

namespace App\Controller\User;

use Carbon\Carbon;
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
        $user->setTokenDate(Carbon::now());
        $this->managerRegistry->getManager()->flush();

        // TODO : send email
        $mail = (new Email())
            ->from($_ENV['MAILER_FROM'])
            ->to($user->getEmail())
            ->subject('Password reset')
            ->html('<p>Hi ' . $user->getEmail() . ',</p>
            <p>Click on the link below to reset your password.</p>
            <p><a href="https://fightclub-antoinepollet.vercel.app/users/validate/password?token=' . $token . '">Reset password</a></p>
            <p>The validity of this link is <b>30min</b></p>
            <br />
            <p>Thanks,</p>
            <p>Thunderous Knockout Fighting</p>');

        $this->mailer->send($mail);

        return $this->json('Success');
    }
}
