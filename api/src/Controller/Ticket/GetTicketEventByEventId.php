<?php

namespace App\Controller\Ticket;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetTicketEventByEventId extends AbstractController
{
    public function __construct(
        private readonly EventRepository $eventRepository)
    {}

    public function __invoke(string $id)
    {
        $event = $this->eventRepository->find($id);
        return $event->getTicketEvents();
    }
}
