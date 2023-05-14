<?php

namespace App\Service\Fight;

use App\Entity\Bet;
use App\Entity\Fight;
use App\Enum\WalletTransaction\WalletTransactionStatusEnum;
use Doctrine\Common\Collections\ArrayCollection;

class FightOddsService
{
    private Fight $fight;
    #private ArrayCollection $fighterAOddsArray;
    private float $fighterAOdds;
    #private ArrayCollection $fighterBOddsArray;
    private float $fighterBOdds;

    public function __construct(Fight $fight) {
        $this->fight = $fight;

        $totalAmountBetted = $this->fight->getBets()->reduce(function ($carry, $entity) {
            if ($entity->getWalletTransaction()->getStatus() === WalletTransactionStatusEnum::ACCEPTED) {
                return $carry + $entity->getAmount();
            }

            return $carry;
        });

        $totalAmountBettedOnFighterA = $this->fight->getBets()->reduce(function ($carry, $entity) {
            if ($entity->getWalletTransaction()->getStatus() === WalletTransactionStatusEnum::ACCEPTED && $entity->getBetOn() === $this->fight->getFighterA()) {
                return $carry + $entity->getAmount();
            }

            return $carry;
        });

        $totalAmountBettedOnFighterB = $this->fight->getBets()->reduce(function ($carry, $entity) {
            if ($entity->getWalletTransaction()->getStatus() === WalletTransactionStatusEnum::ACCEPTED && $entity->getBetOn() === $this->fight->getFighterB()) {
                return $carry + $entity->getAmount();
            }

            return $carry;
        });

        if ($totalAmountBettedOnFighterA && $totalAmountBetted && $totalAmountBettedOnFighterB) {
            $this->fighterAOdds = 1 / ( $totalAmountBettedOnFighterA / $totalAmountBetted );
            $this->fighterBOdds = 1 / ( $totalAmountBettedOnFighterB / $totalAmountBetted );
        }

        /*$this->fighterAOddsArray = $this->fight->getBets()->filter(function (Bet $fight_bet) {
            return $fight_bet->getBetOn() === $this->fight->getFighterA();
        });

        $this->fighterBOddsArray = $this->fight->getBets()->filter(function (Bet $fight_bet) {
            return $fight_bet->getBetOn() === $this->fight->getFighterB();
        });*/
    }

    public function odd() {
        return [
            "fighterAOdds" => $this->fighterAOdds,
            "fighterBOdds" => $this->fighterBOdds
        ];
    }
}
