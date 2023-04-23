<?php

namespace App\Controller\Sponsorship;

use App\Entity\Sponsorship;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mime\Email;

#[AsController]
class SendInvitation extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MailerInterface $mailer,
        private readonly UserRepository $userRepository,
        private readonly RequestStack $requestStack,
        private readonly Security $security) {
    }

    public function __invoke(): Response
    {
        $sponsoredMail = json_decode($this->requestStack->getCurrentRequest()->getContent())->sponsored;
        $sponsorId = json_decode($this->requestStack->getCurrentRequest()->getContent())->sponsorId;

        $sponsor = $this->userRepository->find($sponsorId);
        $currentUser = $this->userRepository->find($this->security->getUser()->getId());
        $sponsored = $this->userRepository->findOneBy(['email' => $sponsoredMail]);

        $token = bin2hex(random_bytes(32));
        $sponsored->setVIPToken($token);

        if ($sponsor->getId() == $currentUser->getId()) {
            $email = (new Email())
                ->from($_ENV['MAILER_FROM'])
                ->to($sponsored->getEmail())
                ->subject('An admin invited you !')
                ->html(
                '<p>Hi ' . $sponsored->getEmail() . ',</p>
                    <p>An admin invited you to become VIP</p>
                    <p><a href="http://localhost:5173/become-vip/?token='.$token.'">Become VIP</a></p>
                    <br />
                    <p>Thanks,</p>
                    <p>Thunderous Knockout Fighting</p>'
                );

            $this->mailer->send($email);

            $sponsorship = New Sponsorship();
            $sponsorship->setEmailValidation(false);
            $sponsorship->setSponsorValidation(false);
            $sponsorship->setSponsor($sponsor);
            $sponsorship->setSponsored($sponsored);

            $this->entityManager->persist($sponsorship);
            $this->entityManager->flush();

            return new Response(null, 200, ["Content-Type" => "application/json"]);

        } else {
            return new Response(null, 404);
        }
    }
}
