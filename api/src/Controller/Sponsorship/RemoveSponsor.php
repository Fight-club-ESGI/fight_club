<?php

namespace App\Controller\Sponsorship;

use App\Repository\SponsorshipRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
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

        if($sponsorship == null) {
            throw new BadRequestException("Entity not found");
        }

        $this->entityManager->remove($sponsorship);
        $this->entityManager->flush();

        return new Response($sponsorshipId, 200);
    }

}
