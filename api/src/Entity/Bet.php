<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BetRepository::class)]
#[ApiResource]
class Bet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fight $fight = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $betOn = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $bettor = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFight(): ?Fight
    {
        return $this->fight;
    }

    public function setFight(?Fight $fight): self
    {
        $this->fight = $fight;

        return $this;
    }

    public function getBetOn(): ?User
    {
        return $this->betOn;
    }

    public function setBetOn(?User $betOn): self
    {
        $this->betOn = $betOn;

        return $this;
    }

    public function getBettor(): ?User
    {
        return $this->bettor;
    }

    public function setBettor(?User $bettor): self
    {
        $this->bettor = $bettor;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
