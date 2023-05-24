<?php

namespace App\Controller\Sponsorship;

use App\Entity\Sponsorship;
use App\Entity\User;
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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class SendInvitation extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MailerInterface $mailer,
        private readonly UserRepository $userRepository,
        private readonly RequestStack $requestStack,
        private readonly Security $security,
        private readonly UserPasswordHasherInterface $passwordHasher) {
    }

    public function __invoke(): Response
    {
        $sponsoredMail = json_decode($this->requestStack->getCurrentRequest()->getContent())->sponsoredEmail;
        $sponsorId = json_decode($this->requestStack->getCurrentRequest()->getContent())->sponsorId;

        $sponsor = $this->userRepository->find($sponsorId);
        $currentUser = $this->userRepository->find($this->security->getUser()->getId());
        $sponsored = $this->userRepository->findOneBy(['email' => $sponsoredMail]);

        if($sponsored) {
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
                    <p><a href="https://fightclub-antoinepollet.vercel.app/become-vip/?token='.$token.'">Become VIP</a></p>
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
                return new Response("Failed to send mail, please try again", 400);
            }
        } else {
            $generatedPassword = randomPassword();

            $user = new User();
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $generatedPassword
            );
            $user->setEmail($sponsoredMail);
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($hashedPassword);

            $email = (new Email())
                ->from($_ENV['MAILER_FROM'])
                ->to($user->getEmail())
                ->subject('An admin invited you !')
                ->html(
                    '<p>Hi ' . $user->getEmail() . ',</p>
                    <p>An admin invited you to the platform and to become VIP</p>
                    <p>Use this password to login to your account : '. $generatedPassword .'</p>
                    <p><a href="https://fightclub-antoinepollet.vercel.app/">Click this link to access our platform</a></p>
                    <br />
                    <p>Thanks,</p>
                    <p>Thunderous Knockout Fighting</p>'
                );

            $this->mailer->send($email);

            $sponsorship = New Sponsorship();
            $sponsorship->setEmailValidation(true);
            $sponsorship->setSponsorValidation(false);
            $sponsorship->setSponsor($sponsor);
            $sponsorship->setSponsored($user);

            $this->entityManager->persist($user);
            $this->entityManager->persist($sponsorship);
            $this->entityManager->flush();
            return new Response(null, 200, ["Content-Type" => "application/json"]);
        }
    }
}

function randomPassword(): string {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
