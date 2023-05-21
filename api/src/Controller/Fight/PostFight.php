<?php

namespace App\Controller\Fight;

use App\Entity\Fight;
use App\Repository\EventRepository;
use App\Repository\FighterRepository;
use App\Repository\UserRepository;
use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\SecurityBundle\Security;

#[AsController]
class PostFight extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FighterRepository $fighterRepository,
        private readonly EventRepository $eventRepository
    ) {
    }

    public function __invoke(Request $request): Fight
    {
        $eventIRI = json_decode($request->getContent())->event;
        $fighterAIRI = json_decode($request->getContent())->fighterA;
        $fighterBIRI = json_decode($request->getContent())->fighterB;
        $fightDate = json_decode($request->getContent())->fightDate;

        if(!$eventIRI or !$fighterAIRI or !$fighterBIRI) {
            throw new BadRequestException();
        }
        $eventId = explode( '/', $eventIRI);
        $eventId = end($eventId);

        $fighterAId = explode( '/', $fighterAIRI);
        $fighterAId = end($fighterAId);

        $fighterBId = explode( '/', $fighterBIRI);
        $fighterBId = end($fighterBId);

        $event = $this->eventRepository->find($eventId);
        $fighterA = $this->fighterRepository->find($fighterAId);
        $fighterB = $this->fighterRepository->find($fighterBId);

        $timeStart = $event->getTimeStart();
        $now = new Carbon();

        if ($now > $timeStart) {
            throw new BadRequestException("The event has already started");
        }

        $fight = new Fight();
        $fight->setFightDate(new DateTime($fightDate));
        $fight->setEvent($event);
        $fight->setFighterA($fighterA);
        $fight->setFighterB($fighterB);

        // $fighterA->addFight($fight);
        // $fighterB->addFight($fight);

        $this->entityManager->persist($fight);
        // $this->entityManager->persist($fighterA);
        // $this->entityManager->persist($fighterB);

        $this->entityManager->flush();

        return $fight;
    }
}
