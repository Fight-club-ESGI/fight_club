<?php

namespace App\Controller\Event;

use App\Entity\Event;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UpdateEvent extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(Request $request, Event $event): Event
    {
        $propertiesToUpdate = $request->request->all();

        $file = $request->files->get('imageFile');

        if ($file instanceof UploadedFile) {
            $event->setImageFile($file);
        }

        foreach ($propertiesToUpdate as $propertyName => $propertyValue) {
            $setterMethodName = 'set' . ucfirst($propertyName);
            if (method_exists($event, $setterMethodName)) {
                switch ($propertyName) {
                    case 'timeStart':
                    case 'timeEnd':
                        $propertyValue = date_timestamp_set(new DateTime, strtotime($propertyValue));
                        break;
                    case 'vip':
                        $propertyValue = filter_var($propertyValue, FILTER_VALIDATE_BOOLEAN);
                        break;
                    case 'capacity':
                        $propertyValue = intval($propertyValue);
                        break;
                }

                $event->$setterMethodName($propertyValue);
            }
        }

        $this->entityManager->flush();

        return $event;
    }
}