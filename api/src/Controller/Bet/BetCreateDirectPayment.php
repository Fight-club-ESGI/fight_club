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
    public function __construct(
        private readonly CheckoutService $checkoutService,
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly UserRepository $userRepository
    ) {
    }

    public function __invoke(Request $request, Bet $bet): Response
    {
        if ($_SERVER['REQUEST_TIME'] > $bet->getFight()->getEvent()->getTimeEnd()->getTimestamp()) {
            return new Response('the event already finished, you cannot bet anymore', 400);
        } else if ($_SERVER['REQUEST_TIME'] > $bet->getFight()->getEvent()->getTimeStart()->getTimestamp()) {
            return new Response('the event already started, you cannot bet anymore', 400);
        }


        if (!($bet->getBetOn()->getId() === $bet->getFight()->getFighterB()->getId()) && !($bet->getBetOn()->getId() === $bet->getFight()->getFighterA()->getId())) {
            return new Response('user don\'t belong to this fight', 200);
        }

        $user = $this->userRepository->find($this->security->getUser()->getId());
        $bet->setBettor($user);

        $checkout_session = $this->checkoutService->checkout(
            $user,
            $bet->getAmount(),
            WalletTransactionTypeEnum::BET,
        );

        $bet->setStatus(BetStatusEnum::PENDING);
        $bet->setWalletTransaction($this->checkoutService->getWalletTransaction());

        $this->entityManager->persist($bet);
        $this->entityManager->flush();

        $this->checkoutService->getWalletTransaction()->setBet($bet);

        $this->entityManager->persist($this->checkoutService->getWalletTransaction());
        $this->entityManager->flush();

        return new Response($checkout_session->url, 200, ["Content-Type" => "application/json"]);
    }
}
