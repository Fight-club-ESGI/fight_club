<?php

namespace App\Controller\Sponsorship;

use App\Repository\SponsorshipRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class AcceptRequest extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly SponsorshipRepository $sponsorshipRepository,
        private readonly SerializerInterface $serializer)
    {}

    public function __invoke(string $sponsorshipId): Response
    {
        $sponsorship = $this->sponsorshipRepository->find($sponsorshipId);
        $sponsor = $this->userRepository->find($this->security->getUser()->getId());

        if ($sponsorship->getSponsor()->getId() === $sponsor->getId()) {
            $sponsorship->setSponsorValidation(true);

            $sponsor = $this->userRepository->find($sponsorship->getSponsored());
            $sponsor->setRoles(["ROLE_VVIP"]);

            $this->entityManager->flush();

            $response = array('sponsorship' => $sponsorship);

            $jsonObject = $this->serializer->serialize($response, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);

            return new Response($jsonObject, 200, ["Content-Type" => "application/json"]);
        } else {
            return new Response(null, 404);
        }
    }
}
