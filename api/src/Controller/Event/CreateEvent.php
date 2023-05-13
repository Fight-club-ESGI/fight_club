<?php

namespace App\Controller\Event;

use App\Entity\Event;
use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CreateEvent extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(Request $request): Event | BadRequestException
    {
        $file = $request->files->get('imageFile');
        $parameters = $request->request->all();

        $event = new Event();
        $event->setName($parameters['name']);
        $event->setDescription($parameters['description'] ?? null);
        $event->setLocation($parameters['location']);
        $event->setLocationLink($parameters['locationLink'] ?? null);
        $event->setTimeStart(date_timestamp_set(new DateTime, strtotime($parameters['timeStart'])));
        $event->setTimeEnd(date_timestamp_set(new DateTime, strtotime($parameters['timeEnd'])));
        $event->setCapacity(intval($parameters['capacity']));
        $event->setVip(filter_var($parameters['vip'], FILTER_VALIDATE_BOOLEAN));
        $event->setFightCategory($parameters['fightCategory'] ?? null);
        $event->setImageFile($file ?? null);
        $event->setUpdatedAt(new Carbon);


        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $event;
    }
}