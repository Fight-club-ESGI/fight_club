<?php

namespace App\Controller\Sponsorship;

use App\Entity\Sponsorship;
use App\Repository\SponsorshipRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class AcceptRequest extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly SponsorshipRepository $sponsorshipRepository,
        private readonly Security $security)
    {}

    public function __invoke(string $sponsorshipId): Response
    {
        $sponsorship = $this->sponsorshipRepository->find($sponsorshipId);
        $sponsor = $this->userRepository->find($this->security->getUser()->getId());

        if ($sponsorship->getSponsor()->getId() == $sponsor->getId()) {
            $sponsorship->setSponsorValidation(true);

            $sponsor = $this->userRepository->find($sponsorship->getSponsored());
            $sponsor->setRoles(["ROLE_VVIP"]);

            $this->entityManager->flush();

            return new Response("Success", 200);
        } else {
            throw new BadRequestException("Failed to set VIP role");
        }
    }
}
