<?php

namespace App\Controller\Event;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class EventTickets extends AbstractController
{
    public function __construct(
        private readonly EventRepository $eventRepository,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function __invoke(string $eventId): Response
    {
        $event = $this->eventRepository->find($eventId);

        $jsonObject = $this->serializer->serialize($event->getTickets(), 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($jsonObject, 200, ["Content-Type" =>  "application/json"]);
    }
}
