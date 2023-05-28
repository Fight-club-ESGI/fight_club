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
                    'id' => $event->getId(),
                    'name' => $event->getName(),
                    'timeStart' => $event->getTimeStart(),
                    'timeEnd' => $event->getTimeEnd(),
                    'location' => $event->getLocation(),
                    'description' => $event->getDescription(),
                    'fights' => $event->getFights(),
                ],
                'category' => $ticketEvent->getTicketCategory()->getName(),
                'price' => $ticketEvent->getPrice(),
                'order' => [
                    'id' => $order->getId(),
                    'reference' => $order->getReference(),
                    'customer' => $order->getUser()->getEmail(),
                    'createdAt' => $order->getCreatedAt(),
                ],
            ];
        }

        return $tickets;
    }
}
