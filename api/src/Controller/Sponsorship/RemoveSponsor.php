<?php

namespace App\Controller\Sponsorship;

use App\Repository\SponsorshipRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RemoveSponsor extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SponsorshipRepository $sponsorshipRepository)
    {}

    public function __invoke(string $sponsorshipId): Response

    {
        $sponsorship = $this->sponsorshipRepository->find($sponsorshipId);

        $this->entityManager->remove($sponsorship);
        $this->entityManager->flush();

        return new Response(null, 200, ["Content-Type" => "application/json"]);
    }

}
