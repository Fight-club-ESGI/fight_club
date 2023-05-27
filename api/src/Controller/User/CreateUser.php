<?php

namespace App\Controller\User;

use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CreateUser extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator)
    {
    }

    public function __invoke(Request $request): User
    {
        $event = new User();

        $propertiesToSet = $request->request->all();

        $file = $request->files->get('imageFile');

        if ($file instanceof UploadedFile) {
            $event->setImageFile($file);
        }

        foreach ($propertiesToSet as $propertyName => $propertyValue) {
            $setterMethodName = 'set' . ucfirst($propertyName);

            if (method_exists($event, $setterMethodName)) {
                switch ($propertyName) {
                    case 'tokenDate':
                        $propertyValue = date_timestamp_set(new DateTime, strtotime($propertyValue));
                        break;
                }

                $event->$setterMethodName($propertyValue);
            }
        }

        $this->validator->validate($event);

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $event;
    }
}