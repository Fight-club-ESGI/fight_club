<?php

namespace App\Controller\Sponsorship;

use App\Repository\SponsorshipRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class SponsorLinkController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly SponsorshipRepository $sponsorshipRepository,
        private readonly SerializerInterface $serializer)
    {}

    public function __invoke(string $sponsoredId): Response

    {
        $sponsored = $this->userRepository->find($sponsoredId);
        $sponsorship = $this->sponsorshipRepository->findOneBy(['sponsored' => $sponsored->getId()]);

        if ($sponsorship) {
            $sponsorship->setEmailValidation(true);

            $this->entityManager->persist($sponsorship);
            $this->entityManager->flush();

            $response = array('sponsorship' => $sponsorship);

            $jsonObject = $this->serializer->serialize($response, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);

            return new Response($jsonObject, 200, ["Content-Type" => "application/json"]);
        }

        return new Response(null, 404);
    }

}
