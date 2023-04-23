<?php

namespace App\Controller\Sponsorship;

use App\Repository\SponsorshipRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

class AcceptRequest extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly SponsorshipRepository $sponsorshipRepository,
        private readonly Security $security)
    {}

    public function __invoke(string $sponsorshipId)
    {
        $sponsorship = $this->sponsorshipRepository->find($sponsorshipId);
        $sponsor = $this->userRepository->find($this->security->getUser()->getId());

        if ($sponsorship->getSponsor()->getId() === $sponsor->getId()) {
            $sponsorship->setSponsorValidation(true);

            $sponsor = $this->userRepository->find($sponsorship->getSponsored());
            $sponsor->setRoles(["ROLE_VVIP"]);

            $this->entityManager->flush();

            return $sponsorship;
        }
    }
}
