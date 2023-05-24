<?php

namespace App\Controller\Fighter;

use App\Entity\Fighter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteFighter extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {}

    public function __invoke(Request $request, Fighter $fighter): Response
    {
        $fighter->setIsActive(false);

        $this->entityManager->flush();

        return new Response(null, 204);
    }
}
