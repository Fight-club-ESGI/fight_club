<?php

namespace App\Controller\Ticket;

use App\Repository\EventRepository;
use App\Repository\TicketEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetTicketEventByEventId extends AbstractController
{
    public function __construct(
        private readonly EventRepository $eventRepository,
        private readonly TicketEventRepository $ticketEventRepository
    ) {
    }

    public function __invoke(string $id)
    {
        $event = $this->ticketEventRepository->getIsActiveTicketEventById($id);
        return $event;
    }
}
