<?php

namespace App\Controller;

use App\Entity\FightBet;
use App\Entity\WalletTransaction;
use App\Enum\FightBetStatusType;
use App\Enum\WalletTransactionStatusType;
use App\Enum\WalletTransactionTypeType;
use App\Repository\FightRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class BetCreateWalletPayment  extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request, Security $security, UserRepository $userRepository, FightRepository $fightRepository, FightBet $fightBet ): Response
    {
        if ($_SERVER['REQUEST_TIME'] > $fightBet->getFight()->getEvent()->getStartTimestamp()->getTimestamp()) {
            return new Response('the event already started, you cannot bet anymore', 400);
        } else if ($_SERVER['REQUEST_TIME'] > $fightBet->getFight()->getEvent()->getEndTimestamp()->getTimestamp()) {
            return new Response('the event already finished, you cannot bet anymore', 400);
        }

        if (!($fightBet->getBetOn()->getId() === $fightBet->getFight()->getFighterB()->getId()) && !($fightBet->getBetOn()->getId() === $fightBet->getFight()->getFighterA()->getId())) {
            return new Response('user don\'t belong to this fight', 400);
        }

        $user = $userRepository->find($security->getUser()->getId());
        $fightBet->setBetUser($user);

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
            $walletTransaction->setStatus(WalletTransactionStatusType::ACCEPTED);
            $walletTransaction->setType(WalletTransactionTypeType::BET);
            $walletTransaction->setTransaction("wallet");
            $this->entityManager->persist($walletTransaction);
            $this->entityManager->flush();
        }

        $fightBet->setStatus(FightBetStatusType::PENDING);

        $this->entityManager->persist($fightBet);
        $this->entityManager->flush();

        return new Response('fund ok', 200);
    }
}
