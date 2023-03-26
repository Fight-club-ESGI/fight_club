<?php

namespace App\Controller\Bet;

use App\Entity\Bet;
use App\Entity\WalletTransaction;
use App\Enum\Bet\BetStatusEnum;
use App\Enum\WalletTransaction\WalletTransactionStatusEnum;
use App\Enum\WalletTransaction\WalletTransactionTypeEnum;
use App\Repository\FightRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;


class BetCreateWalletPayment  extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {
    }

    public function __invoke(Request $request, Security $security, UserRepository $userRepository, FightRepository $fightRepository, Bet $fightBet ): Response
    {
        if ($_SERVER['REQUEST_TIME'] > $fightBet->getFight()->getEvent()->getTimeStart()->getTimestamp()) {
            return new Response('the event already started, you cannot bet anymore', 400);
        } else if ($_SERVER['REQUEST_TIME'] > $fightBet->getFight()->getEvent()->getTimeEnd()->getTimestamp()) {
            return new Response('the event already finished, you cannot bet anymore', 400);
        }

        if (!($fightBet->getBetOn()->getId() === $fightBet->getFight()->getFighterB()->getId()) && !($fightBet->getBetOn()->getId() === $fightBet->getFight()->getFighterA()->getId())) {
            return new Response('user don\'t belong to this fight', 400);
        }

        $user = $userRepository->find($security->getUser()->getId());
        $fightBet->setBettor($user);

        if ($fightBet->getAmount() > $user->getWallet()->getAmount()) {
            return new Response('fund insufficient.', 400);
        } else {
            $wallet = $user->getWallet();
            $wallet->setAmount($user->getWallet()->getAmount() - $fightBet->getAmount());
            $this->entityManager->persist($wallet);
            $this->entityManager->flush();

            $walletTransaction = new WalletTransaction();
            $walletTransaction->setAmount($fightBet->getAmount());
            $walletTransaction->setWallet($wallet);
            $walletTransaction->setStatus(WalletTransactionStatusEnum::ACCEPTED);
            $walletTransaction->setType(WalletTransactionTypeEnum::BET);
            $walletTransaction->setTransaction("wallet");
            $this->entityManager->persist($walletTransaction);
            $this->entityManager->flush();
        }

        $fightBet->setStatus(BetStatusEnum::PENDING);

        $this->entityManager->persist($fightBet);
        $this->entityManager->flush();

        return new Response('fund ok', 200);
    }
}
