<?php

namespace App\Controller\User;

use App\Entity\Fighter;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UpdateUser extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(Request $request, Fighter $event): Fighter
    {
        $propertiesToUpdate = $request->request->all();

        $file = $request->files->get('imageFile');

        if ($file instanceof UploadedFile) {
            $event->setImageFile($file);
        }

        foreach ($propertiesToUpdate as $propertyName => $propertyValue) {
            $setterMethodName = 'set' . ucfirst($propertyName);

            if (method_exists($event, $setterMethodName)) {
                if ($propertyName == 'tokenDate') {
                    $propertyValue = date_timestamp_set(new DateTime, strtotime($propertyValue));
                }

                $event->$setterMethodName($propertyValue);
            }
        }

        $this->entityManager->flush();

        return $event;
    }
}