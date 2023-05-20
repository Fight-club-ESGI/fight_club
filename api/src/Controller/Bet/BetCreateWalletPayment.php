<?php

namespace App\Controller\Bet;

use App\Entity\Bet;
use App\Entity\WalletTransaction;
use App\Enum\Bet\BetStatusEnum;
use App\Enum\WalletTransaction\WalletTransactionStatusEnum;
use App\Enum\WalletTransaction\WalletTransactionTypeEnum;
use App\Repository\EventRepository;
use App\Repository\FightRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class BetCreateWalletPayment extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly UserRepository $userRepository,

    ) {
    }

    public function __invoke(Request $request, Bet $bet): WalletTransaction | Response
    {
        if ($_SERVER['REQUEST_TIME'] > $bet->getFight()->getEvent()->getTimeEnd()->getTimestamp()) {
            return new Response('the event already finished, you cannot bet anymore', 400);
        } else if ($_SERVER['REQUEST_TIME'] > $bet->getFight()->getEvent()->getTimeStart()->getTimestamp()) {
            return new Response('the event already started, you cannot bet anymore', 400);
        }

        if (!($bet->getBetOn()->getId() === $bet->getFight()->getFighterB()->getId()) && !($bet->getBetOn()->getId() === $bet->getFight()->getFighterA()->getId())) {
            return new Response('user don\'t belong to this fight', 400);
        }

        $user = $this->userRepository->find($this->security->getUser()->getId());
        $bet->setBettor($user);

        if ($bet->getAmount() > $user->getWallet()->getAmount()) {
            return new Response('fund insufficient.', 400);
        } else {
            $wallet = $user->getWallet();
            $wallet->setAmount($user->getWallet()->getAmount() - $bet->getAmount());
            $this->entityManager->persist($wallet);
            $this->entityManager->flush();

            $walletTransaction = new WalletTransaction();
            $walletTransaction->setAmount($bet->getAmount());
            $walletTransaction->setWallet($wallet);
            $walletTransaction->setStatus(WalletTransactionStatusEnum::ACCEPTED);
            $walletTransaction->setType(WalletTransactionTypeEnum::BET);
            $walletTransaction->setTransaction("wallet");
            $this->entityManager->persist($walletTransaction);
            $this->entityManager->flush();
        }

        $bet->setStatus(BetStatusEnum::PENDING);
        $bet->setWalletTransaction($walletTransaction);

        $this->entityManager->persist($bet);
        $this->entityManager->flush();
        return $walletTransaction;
    }
}
