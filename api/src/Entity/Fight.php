<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FightRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FightRepository::class)]
#[ApiResource]
class Fight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'fights')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'fights')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $fighterA = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $fighterB = null;

    #[ORM\ManyToOne]
    private ?User $winner = null;

    #[ORM\ManyToOne]
    private ?User $loser = null;

    #[ORM\Column]
    private ?bool $winnerValidation = null;

    #[ORM\ManyToOne]
    private ?User $adminValidatorA = null;

    #[ORM\ManyToOne]
    private ?User $adminValidatorB = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getFighterA(): ?User
    {
        return $this->fighterA;
    }

    public function setFighterA(?User $fighterA): self
    {
        $this->fighterA = $fighterA;

        return $this;
    }

    public function getFighterB(): ?User
    {
        return $this->fighterB;
    }

    public function setFighterB(?User $fighterB): self
    {
        $this->fighterB = $fighterB;

        return $this;
    }

    public function getWinner(): ?User
    {
        return $this->winner;
    }

    public function setWinner(?User $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function getLoser(): ?User
    {
        return $this->loser;
    }

    public function setLoser(?User $loser): self
    {
        $this->loser = $loser;

        return $this;
    }

    public function isWinnerValidation(): ?bool
    {
        return $this->winnerValidation;
    }

    public function setWinnerValidation(bool $winnerValidation): self
    {
        $this->winnerValidation = $winnerValidation;

        return $this;
    }

    public function getAdminValidatorA(): ?User
    {
        return $this->adminValidatorA;
    }

    public function setAdminValidatorA(?User $adminValidatorA): self
    {
        $this->adminValidatorA = $adminValidatorA;

        return $this;
    }

    public function getAdminValidatorB(): ?User
    {
        return $this->adminValidatorB;
    }

    public function setAdminValidatorB(?User $adminValidatorB): self
    {
        $this->adminValidatorB = $adminValidatorB;

        return $this;
    }
}
