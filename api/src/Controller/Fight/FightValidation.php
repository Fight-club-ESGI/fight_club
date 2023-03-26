<?php

namespace App\Controller\Fight;

use App\Entity\Fight;
use App\Entity\Bet;
use App\Entity\WalletTransaction;
use App\Enum\Bet\BetStatusEnum;
use App\Enum\WalletTransaction\WalletTransactionStatusEnum;
use App\Enum\WalletTransaction\WalletTransactionTypeEnum;
use App\Repository\FightRepository;
use App\Repository\UserRepository;
use App\Service\Checkout\CheckoutService;
use App\Service\Fight\FightOddsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\SecurityBundle\Security;

#[AsController]
class FightValidation extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {
    }

    public function __invoke(Request $request, UserRepository $userRepository, Security $security, string $fight_id, FightRepository $fightRepository): Response
    {
        $fight = $fightRepository->find($fight_id);
        if ($fight->isWinnerValidation()) {
            return new Response('This fight is already validated', 200, ["Content-type" => "application/json"]);
        }

        if (is_null($fight->getAdminValidatorA())) {
            $fight->setAdminValidatorA($userRepository->find($security->getUser()->getId()));
        } else if (is_null($fight->getAdminValidatorB()) && ($fight->getAdminValidatorA()->getId() !== $security->getUser()->getId())) {
            $fight->setAdminValidatorB($userRepository->find($security->getUser()->getId()));
        } else if ($fight->getAdminValidatorB()->getId() === $security->getUser()->getId()) {
            return new Response('Another admin should validate this fight', 200, ["Content-Type" => "application/json"]);
        }

        $this->entityManager->persist($fight);
        $this->entityManager->flush();

        if (!is_null($fight->getAdminValidatorA()) && !is_null($fight->getAdminValidatorB())) {
            $fight->setWinnerValidation(true);
            $this->entityManager->persist($fight);
            $this->entityManager->flush();

            $fightBets = $fight->getBets();
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
                    $transaction->setType(WalletTransactionTypeEnum::DEPOSIT);
                    $transaction->setStatus(WalletTransactionStatusEnum::ACCEPTED);
                    $transaction->setTransaction("bet gain");
                    $this->entityManager->persist($transaction);
                    $this->entityManager->flush();

                    $bet->setStatus(BetStatusEnum::WIN);
                } else {
                    $bet->setStatus(BetStatusEnum::LOSE);
                }
                $this->entityManager->persist($bet);
                $this->entityManager->flush();
            }
        }

        return new Response('validated', 200, ["Content-Type" => "application/json"]);
    }
}
