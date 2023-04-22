<?php

namespace App\Controller\Order;

use App\Entity\Event;
use App\Entity\Order;
use App\Entity\Ticket;
use App\Entity\TicketCategory;
use App\Entity\TicketEvent;
use App\Repository\TicketEventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class OrderController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TicketEventRepository $ticketEventRepository,
        private readonly Security $security
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $eventId = $request->get('event_id');
        $ticketCategoryId = $request->get('ticket_category_id');
        $quantity = $request->get('quantity');

        // Trouver l'EventTicket correspondant
        $ticketEvent = $this->ticketEventRepository->findOneBy([
            'event' => $eventId,
            'ticket_category' => $ticketCategoryId,
        ]);

        if (!$ticketEvent) {
            return new Response('Ticket category not available for this event.', Response::HTTP_BAD_REQUEST);
        }

        // Vérifier la disponibilité des billets
        if (count($ticketEvent->getTickets()) + $quantity > $ticketEvent->getMaxQuantity()) {
            return new Response('Not enough tickets available for this category.', Response::HTTP_BAD_REQUEST);
        }

        // Créer la nouvelle commande
        $order = new Order();
        $order->setCustomer($this->security->getUser());

        // Créer les tickets et les ajouter à la commande
        for ($i = 0; $i < $quantity; $i++) {
            $ticket = (new Ticket())
                ->setTicketEvent($ticketEvent)
                ->setPrice($ticketEvent->getPrice())
                ->setEvent($ticketEvent->getEvent())
                ->setTicketCategory($ticketEvent->getTicketCategory())
                ->setOrder($order)
                ->setReference();

            $order->addTicket($ticket);
        }

        // Enregistrer la commande et les tickets
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return new Response('Order successfully created.', Response::HTTP_CREATED);
    }
}
