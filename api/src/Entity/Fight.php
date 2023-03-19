<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\FightRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FightRepository::class)]
#[ApiResource]
class Fight
{
    use EntityIdTrait;
    use TimestampableTrait;

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
    private ?bool $winnerValidation = false;

    #[ORM\ManyToOne]
    private ?User $adminValidatorA = null;

    #[ORM\ManyToOne]
    private ?User $adminValidatorB = null;

    #[ORM\OneToMany(mappedBy: 'fight', targetEntity: Bet::class)]
    private Collection $bets;

    public function __construct()
    {
        $this->bets = new ArrayCollection();
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

    /**
     * @return Collection<int, Bet>
     */
    public function getBets(): Collection
    {
        return $this->bets;
    }

    public function addBet(Bet $bet): self
    {
        if (!$this->bets->contains($bet)) {
            $this->bets->add($bet);
            $bet->setFight($this);
        }

        return $this;
    }

    public function removeBet(Bet $bet): self
    {
        if ($this->bets->removeElement($bet)) {
            // set the owning side to null (unless already changed)
            if ($bet->getFight() === $this) {
                $bet->setFight(null);
            }
        }

        return $this;
    }
}
