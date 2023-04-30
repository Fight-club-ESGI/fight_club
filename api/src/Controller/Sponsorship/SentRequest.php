<?php

namespace App\Controller\Sponsorship;

use App\Repository\SponsorshipRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class SentRequest extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SponsorshipRepository $sponsorshipRepository,
        private readonly SerializerInterface $serializer,
        private readonly Security $security)
    {}

    public function __invoke(): Response
    {
        $sponsor = $this->userRepository->find($this->security->getUser()->getId());
        $sponsorship = $this->sponsorshipRepository->findBy(['sponsor' => $sponsor->getId(), 'emailValidation' => true, 'sponsorValidation' => false]);

        return $sponsorship;
    }
}
