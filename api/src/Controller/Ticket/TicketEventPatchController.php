<?php

namespace App\Controller\Ticket;

use App\Entity\TicketEvent;
use App\Repository\TicketEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;


#[AsController]
class TicketEventPatchController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TicketEventRepository $ticketEventRepository
        )
    {}

    public function __invoke(Request $request, string $id): TicketEvent
    {
        $ticketEvent = $this->ticketEventRepository->find($id);
        $newTicketEvent = null;

        if (!$ticketEvent){
            throw new BadRequestException("Ticket event not found");
        }

        $params = json_decode($request->getContent());
        // dd($params->maxQuantity);
        if(floatval($params->price) === $ticketEvent->getPrice()) //modifier la quantity ou supprimer (soft) le ticketEvent
        {
            //dd("price the same");
            $ticketEvent
                ->setMaxQuantity($params->maxQuantity)
                ->setIsActive($params->isActive ?? true);

            $this->entityManager->persist($ticketEvent);
            $this->entityManager->flush();

        }
        else //Modifier le prix
            {
                // dd("price not the same");

                $ticketEvent->setIsActive(false);
                $this->entityManager->persist($ticketEvent);
                $this->entityManager->flush();

                $newTicketEvent = new TicketEvent();
                $newTicketEvent
                    ->setEvent($ticketEvent->getEvent())
                    ->setTicketCategory($ticketEvent->getTicketCategory())
                    ->setMaxQuantity($params->maxQuantity)
                    ->setPrice($params->price)
                    ->setIsActive(true);

                $this->entityManager->persist($newTicketEvent);
                $this->entityManager->flush();

            }

        return $newTicketEvent ?? $ticketEvent;
    }
}
