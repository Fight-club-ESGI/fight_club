<?php

namespace App\Controller\Fight;

use App\Entity\Fight;
use App\Enum\WalletTransaction\WalletTransactionTypeEnum;
use App\Repository\FighterRepository;
use App\Repository\FightRepository;
use App\Repository\UserRepository;
use App\Service\Checkout\CheckoutService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class FightChooseWinner extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $entityManager) {
    }

    public function __invoke(Request $request, Security $security, string $fight_id, FightRepository $fightRepository, FighterRepository $fighterRepository): Response
    {
        $fight = $fightRepository->find($fight_id);

        $parameters = json_decode($request->getContent(), true);
        $fight->setWinner($fighterRepository->find($parameters['winner_id']));

        if($fight->isWinnerValidation()) {
            return new Response('Winner as already be validated', 200, ["Content-Type" => "application:json"]);
        }

        if (($fight->getWinner()->getId() === $fight->getFighterA()) || ($fight->getWinner()->getId() === $fight->getFighterB()->getId())) {
            if ($fight->getWinner() === $fight->getLoser()) {
                return new Response('Winner and Loser cannot be the same person');
            }
        } else {
            return new Response('Winner and Loser must belong to the same fight');
        }

        if ($fight->getWinner() === $fight->getFighterA()) {
            $fight->setLoser($fight->getFighterB());
        } else {
            $fight->setLoser($fight->getFighterA());
        }

        $this->entityManager->persist($fight);
        $this->entityManager->flush();

        return new Response('', 200, ["Content-Type" => "application/json"]);
    }
}
