<?php

namespace App\Controller\User;

use Carbon\Carbon;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CheckTokenValidityController extends AbstractController
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry
    ) {
    }

    public function __invoke(Request $request, string $token): Response | BadRequestException
    {
        /** @var User $user */
        if (!$user = $this->managerRegistry->getRepository(User::class)->findOneBy(['token' => $token])) {
            throw $this->createNotFoundException();
        }

        $tokenDateValidity = new Carbon($user->getTokenDate());
        $tokenDateValidity->addMinutes(30);
        $tokenValidity = $tokenDateValidity->greaterThan(Carbon::now());

        if ($tokenValidity) {
            return new Response("success", 200);
        }

        throw new BadRequestException("Token validity has expired, please try again", 400);
    }
}
