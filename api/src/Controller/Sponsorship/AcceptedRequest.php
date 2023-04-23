<?php

namespace App\Controller\Sponsorship;

use App\Repository\SponsorshipRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

class AcceptedRequest extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SponsorshipRepository $sponsorshipRepository,
        private readonly Security $security)
    {}

    public function __invoke()
    {
        $sponsor = $this->userRepository->find($this->security->getUser()->getId());
        return $this->sponsorshipRepository->findBy(['sponsor' => $sponsor->getId(), 'emailValidation' => true, 'sponsorValidation' => true]);
    }
}
