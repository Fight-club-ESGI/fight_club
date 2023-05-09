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
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly Security $security
    ) {
    }

    public function __invoke(Request $request, Fight $fight): Response
    {
        if ($fight->isWinnerValidation()) {
            return new Response('This fight is already validated', 400, ["Content-type" => "application/json"]);
        }

        if (is_null($fight->getAdminValidatorA())) {
            $fight->setAdminValidatorA($this->userRepository->find($this->security->getUser()->getId()));
        } else if (is_null($fight->getAdminValidatorB()) && ($fight->getAdminValidatorA()->getId() !== $this->security->getUser()->getId())) {
            $fight->setAdminValidatorB($this->userRepository->find($this->security->getUser()->getId()));
        } else if ($fight->getAdminValidatorA()->getId() === $this->security->getUser()->getId()) {
            return new Response('Another admin should validate this fight', 400, ["Content-Type" => "application/json"]);
        }

        $this->entityManager->persist($fight);
        $this->entityManager->flush();

        if (!is_null($fight->getAdminValidatorA()) && !is_null($fight->getAdminValidatorB())) {
            $fight->setWinnerValidation(true);
            $this->entityManager->persist($fight);
            $this->entityManager->flush();

            $fightBets = $fight->getBets();

            $totalAmountBetted = $fightBets->reduce(function ($carry, $entity) {
                if ($entity->getWalletTransaction()->getStatus() === WalletTransactionStatusEnum::ACCEPTED) {
                    return $carry + $entity->getAmount();
                }

                return $carry;
            });

            $totalAmountWinnerBetted = $fightBets->reduce(function ($carry, $entity) use ($fight) {
                if ($entity->getWalletTransaction()->getStatus() === WalletTransactionStatusEnum::ACCEPTED && $entity->getBetOn() === $fight->getWinner()) {
                    return $carry + $entity->getAmount();
                }

                return $carry;
            });

            foreach ( $fightBets as $bet ) {
                if ($bet->getStatus() !== BetStatusEnum::PENDING) break;
                if ($bet->getBetOn() === $fight->getWinner() && $fight->isWinnerValidation()){
                    if($bet->getWalletTransaction()->getStatus() === WalletTransactionStatusEnum::ACCEPTED) {
                        $percentageBetted = $bet->getAmount() / $totalAmountWinnerBetted;
                        $gain = floor($totalAmountBetted * $percentageBetted);

                        $commission = ceil($gain * 0.05);

                        $amount = $gain - $commission;

                        $wallet = $bet->getBettor()->getWallet();
                        $wallet->setAmount($bet->getBettor()->getWallet()->getAmount() + $amount);
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
                        $bet->setStatus(BetStatusEnum::REJECTED);
                    }
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
