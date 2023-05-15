<?php

namespace App\Controller\Fighter;

use App\Entity\Fighter;
use App\Enum\Fight\FighterGenderEnum;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UpdateFighter extends AbstractController
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
                switch ($propertyName) {
                    case 'height':
                    case 'weight':
                        $propertyValue = intval($propertyValue);
                        break;
                    case 'birthdate':
                        $propertyValue = date_timestamp_set(new DateTime, strtotime($propertyValue));
                        break;
                    case 'gender':
                        switch ($propertyValue) {
                            case 'female':
                                $propertyValue = FighterGenderEnum::FEMALE;
                                break;
                            case 'male':
                                $propertyValue = FighterGenderEnum::MALE;
                                break;
                        }
                        break;

                }

                $event->$setterMethodName($propertyValue);
            }
        }

        $this->entityManager->flush();

        return $event;
    }
}