<?php

namespace App\Controller\Bet;

use App\Entity\Fight;
use App\Entity\Bet;
use App\Entity\WalletTransaction;
use App\Enum\Bet\BetStatusEnum;
use App\Enum\WalletTransaction\WalletTransactionStatusEnum;
use App\Enum\WalletTransaction\WalletTransactionTypeEnum;
use App\Repository\FightRepository;
use App\Repository\UserRepository;
use App\Service\Checkout\CheckoutService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BetCreateDirectPayment  extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CheckoutService $checkout;

    public function __construct(CheckoutService $checkoutService, EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->checkout = $checkoutService;
    }

    public function __invoke(Request $request, Security $security, UserRepository $userRepository, FightRepository $fightRepository, Bet $bet): Response
    {
        /*if ($_SERVER['REQUEST_TIME'] > $fightBet->getFight()->getEvent()->getStartTimestamp()->getTimestamp()) {
            if ($_SERVER['REQUEST_TIME'] > $fightBet->getFight()->getEvent()->getEndTimestamp()->getTimestamp()) {
                return new Response('the event already finished, you cannot bet anymore', 200);
            } else {
                return new Response('the event already started, you cannot bet anymore', 200);
            }
        }*/

        if (!($bet->getBetOn()->getId() === $bet->getFight()->getFighterB()->getId()) && !($bet->getBetOn()->getId() === $bet->getFight()->getFighterA()->getId())) {
            return new Response('user don\'t belong to this fight', 200);
        }

        $user = $userRepository->find($security->getUser()->getId());
        $bet->setBetUser($user);

        $checkout_session = $this->checkout->checkout(
            $user,
            $bet->getAmount(),
            WalletTransactionTypeEnum::BET,
        );

        $bet->setStatus(BetStatusEnum::PENDING);

        $this->entityManager->persist($bet);
        $this->entityManager->flush();

        return new Response($checkout_session->url, 200, ["Content-Type" => "application/json"]);
    }
}
