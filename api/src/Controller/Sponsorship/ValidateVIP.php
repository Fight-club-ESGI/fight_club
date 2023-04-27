<?php

namespace App\Controller\Sponsorship;

use App\Repository\SponsorshipRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ValidateVIP extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly SponsorshipRepository $sponsorshipRepository)
    {}

    public function __invoke(string $token)

    {
        $sponsored = $this->userRepository->findOneBy(['VIPToken' => $token]);

        if($sponsored == null) {
            throw new BadRequestException("An error occurred, contact an administrator");
        }

        $sponsorship = $this->sponsorshipRepository->findOneBy(['sponsored' => $sponsored->getId()]);

        if ($sponsorship) {
            $sponsorship->setEmailValidation(true);

            $sponsored->setVIPToken(null);
            $this->entityManager->persist($sponsorship);
            $this->entityManager->flush();

            return "Success";
        }
    }

}
