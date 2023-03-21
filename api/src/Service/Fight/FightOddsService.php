<?php

namespace App\Service\Fight;

use App\Entity\Bet;
use App\Entity\Fight;
use Doctrine\Common\Collections\ArrayCollection;

class FightOddsService
{
    private Fight $fight;
    private ArrayCollection $fighterAOddsArray;
    private int $fighterAOdds;
    private ArrayCollection $fighterBOddsArray;
    private int $fighterBOdds;

    public function __construct(Fight $fight) {
        $this->fight = $fight;

        $this->fighterAOddsArray = $this->fight->getBets()->filter(function (Bet $fight_bet) {
            return $fight_bet->getBetOn() === $this->fight->getFighterA();
        });

        $this->fighterBOddsArray = $this->fight->getBets()->filter(function (Bet $fight_bet) {
            return $fight_bet->getBetOn() === $this->fight->getFighterB();
        });

        if (count($this->fighterAOddsArray) >= count($this->fighterBOddsArray)) {
            $this->fighterAOdds = count($this->fighterAOddsArray) ?: 1  / count($this->fighterBOddsArray) ?: 1;
            $this->fighterBOdds = 1;
        } else {
            $this->fighterBOdds = count($this->fighterBOddsArray) ?: 1 / count($this->fighterAOddsArray) ?: 1;
            $this->fighterAOdds = 1;
        }
    }

    public function odd() {
        return [
            "fighterAOdds" => $this->fighterAOdds,
            "fighterBOdds" => $this->fighterBOdds
        ];
    }

    public function winnerOdds() {
        if ($this->fight->getWinner()->getId() === $this->fight->getFighterA()->getId()) {
            return $this->fighterAOdds / $this->fighterBOdds;
        } else if ($this->fight->getWinner()->getId() === $this->fight->getFighterB()->getId()) {
            return $this->fighterBOdds / $this->fighterAOdds;
        } else {
            return 'no winner';
        }
    }
}
