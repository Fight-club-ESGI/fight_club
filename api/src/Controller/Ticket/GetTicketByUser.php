<?php

namespace App\Controller\Ticket;

use App\Repository\EventRepository;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

class GetTicketByUser extends AbstractController
{
    public function __construct(
        private readonly TicketRepository $ticketRepository,
        private readonly Security $security,
    ) {
    }

    public function __invoke(): array
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $ticketsRes = $this->ticketRepository->findByUserAndOrderStatus($user, 'success');

        $tickets = [];

        foreach ($ticketsRes as $ticket) {
            $order = $ticket->getOrder();
            $ticketEvent = $ticket->getTicketEvent();
            $event = $ticketEvent->getEvent();

            $tickets[] = [
                'id' => $ticket->getId(),
                'reference' => $ticket->getReference(),
                'event' => [
                    'name' => $event->getName(),
                    'time_start' => $event->getTimeStart(),
                    'time_end' => $event->getTimeEnd(),
                    'location' => $event->getLocation(),
                    'description' => $event->getDescription(),
                ],
                'category' => [
                    'name' => $ticketEvent->getTicketCategory()->getName(),
                ],
                'price' => $ticketEvent->getPrice(),
                'order' => [
                    'id' => $order->getId(),
                    'reference' => $order->getReference(),
                    'createdAt' => $order->getCreatedAt(),
                ],
            ];
        }

        return $tickets;
    }
}
