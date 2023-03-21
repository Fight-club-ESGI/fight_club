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
use App\Service\FightOddsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class FightValidation extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request, UserRepository $userRepository, Security $security, string $fight_id, FightRepository $fightRepository): Response
    {
        $fight = $fightRepository->find($fight_id);
        if ($fight->isWinnerValidation()) {
            return new Response('This fight is already validated', 200, ["Content-type" => "application/json"]);
        }

        if (is_null($fight->getAdminValidatorOne())) {
            $fight->setAdminValidatorOne($userRepository->find($security->getUser()->getId()));
        } else if (is_null($fight->getAdminValidatorTwo()) && ($fight->getAdminValidatorOne()->getId() !== $security->getUser()->getId())) {
            $fight->setAdminValidatorTwo($userRepository->find($security->getUser()->getId()));
        } else if ($fight->getAdminValidatorOne()->getId() === $security->getUser()->getId()) {
            return new Response('Another admin should validate this fight', 200, ["Content-Type" => "application/json"]);
        }

        $this->entityManager->persist($fight);
        $this->entityManager->flush();

        if (!is_null($fight->getAdminValidatorOne()) && !is_null($fight->getAdminValidatorTwo())) {
            $fight->setWinnerValidation(true);
            $this->entityManager->persist($fight);
            $this->entityManager->flush();

            $fightBets = $fight->getFightBets();
            foreach ( $fightBets as $bet ) {
                if ($bet->getBetOn() === $fight->getWinner() && $fight->isWinnerValidation()){
                    $fight_odds = new FightOddsService($fight);
                    $amount = $bet->getAmount() + ($bet->getAmount() * $fight_odds->winnerOdds());

                    $wallet = $bet->getBetUser()->getWallet();
                    $wallet->setAmount($bet->getBetUser()->getWallet()->getAmount() + $amount);
                    $this->entityManager->persist($wallet);
                    $this->entityManager->flush();

                    $transaction = new WalletTransaction();
                    $transaction->setWallet($wallet);
                    $transaction->setAmount($amount);
                    $transaction->setType(WalletTransactionTypeType::DEPOSIT);
                    $transaction->setStatus(WalletTransactionStatusType::ACCEPTED);
                    $transaction->setTransaction("bet gain");
                    $this->entityManager->persist($transaction);
                    $this->entityManager->flush();

                    $bet->setStatus(FightBetStatusType::WIN);
                    $this->entityManager->persist($bet);
                    $this->entityManager->flush();
                } else {
                    $bet->setStatus(FightBetStatusType::LOSE);
                    $this->entityManager->persist($bet);
                    $this->entityManager->flush();
                }
            }
        }

        return new Response('validated', 200, ["Content-Type" => "application/json"]);
    }
}
