<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Enum\Bet\BetStatusEnum;
use App\Repository\BetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BetRepository::class)]
#[ApiResource]
class Bet
{
    use EntityIdTrait;
    use TimestampableTrait;

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
    private ?float $amount = 0.00;

    #[ORM\Column(length: 255)]
    private ?BetStatusEnum $status = BetStatusEnum::PENDING;

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

    public function getStatus(): ?BetStatusEnum
    {
        return $this->status;
    }

    public function setStatus(BetStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }
}
