<?php

namespace App\Controller;

use App\Entity\Fight;
use App\Entity\FightBet;
use App\Entity\WalletTransaction;
use App\Enum\FightBetStatusType;
use App\Enum\WalletTransactionStatusType;
use App\Enum\WalletTransactionTypeType;
use App\Repository\FightRepository;
use App\Repository\UserRepository;
use App\Service\CheckoutService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class BetCreateDirectPayment  extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CheckoutService $checkout;

    public function __construct(CheckoutService $checkoutService, EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->checkout = $checkoutService;
    }

    public function __invoke(Request $request, Security $security, UserRepository $userRepository, FightRepository $fightRepository, FightBet $fightBet): Response
    {
        /*if ($_SERVER['REQUEST_TIME'] > $fightBet->getFight()->getEvent()->getStartTimestamp()->getTimestamp()) {
            if ($_SERVER['REQUEST_TIME'] > $fightBet->getFight()->getEvent()->getEndTimestamp()->getTimestamp()) {
                return new Response('the event already finished, you cannot bet anymore', 200);
            } else {
                return new Response('the event already started, you cannot bet anymore', 200);
            }
        }*/

        if (!($fightBet->getBetOn()->getId() === $fightBet->getFight()->getFighterB()->getId()) && !($fightBet->getBetOn()->getId() === $fightBet->getFight()->getFighterA()->getId())) {
            return new Response('user don\'t belong to this fight', 200);
        }

        $user = $userRepository->find($security->getUser()->getId());
        $fightBet->setBetUser($user);

        $checkout_session = $this->checkout->checkout(
            $user,
            $fightBet->getAmount(),
            WalletTransactionTypeType::BET,
        );

        $fightBet->setStatus(FightBetStatusType::PENDING);

        $this->entityManager->persist($fightBet);
        $this->entityManager->flush();

        return new Response($checkout_session->url, 200, ["Content-Type" => "application/json"]);
    }
}
