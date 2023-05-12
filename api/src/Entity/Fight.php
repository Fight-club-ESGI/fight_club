<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\Fight\FightChooseWinner;
use App\Controller\Fight\FightValidation;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\FightRepository;
use App\Service\Fight\FightOddsService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: FightRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Post(
            uriTemplate: "/fights/{fight}/validation",
            controller: FightValidation::class,
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "admin only",
            read: false,
            name: 'fight_wallet',
        ),
        new Patch(
            inputFormats: [
                'json' => ['application/json']
            ],
            normalizationContext: ["groups" => ['fights:get']],
            denormalizationContext: ["groups" => ['fights:post']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Post(
            uriTemplate: "/fights/{fight}/winner",
            controller: FightChooseWinner::class,
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "admin only",
            read: false,
            name: 'fight_winner'
        ),
        new GetCollection(
            normalizationContext: ["groups" => ['fights:get']],
            name: "get_fights"
        ),
        new Get(
            normalizationContext: ["groups" => ['fights:get']],
            name: "get_fight",
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
        )
    ]
)]
class Fight
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\ManyToOne(inversedBy: 'fights')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'fights:get',
        'admin:get',
        'admin:post',
        'fights:get'
    ])]
    #[MaxDepth(1)]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'fightsA')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'fights:get',
        'admin:get',
        'admin:post',
    ])]
    #[MaxDepth(1)]
    private ?Fighter $fighterA = null;

    #[ORM\ManyToOne(inversedBy: 'fightsB')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'fights:get',
        'admin:get',
        'admin:post',
    ])]
    #[MaxDepth(1)]
    private ?Fighter $fighterB = null;

    #[ORM\ManyToOne(inversedBy: 'winners')]
    #[Groups([
        'fights:get',
        'admin:get',
        'admin:post',
    ])]
    #[MaxDepth(1)]
    private ?Fighter $winner = null;

    #[ORM\ManyToOne(inversedBy: 'losers')]
    #[Groups([
        'fights:get',
        'admin:get',
        'admin:post'
    ])]
    #[MaxDepth(1)]
    private ?Fighter $loser = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups([
        'fights:get',
        'admin:get'
    ])]
    private ?bool $winnerValidation = false;

    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'admin:get',
    ])]
    #[MaxDepth(1)]
    private ?number $ratingFighterA;

    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'admin:get',
    ])]
    #[MaxDepth(1)]
    private ?number $ratingFighterB;

    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'admin:get',
    ])]
    #[MaxDepth(1)]
    private ?number $ratingNullMatch;

    #[ORM\ManyToOne]
    #[Groups([
        'fights:get',
        'admin:get'
    ])]
    #[MaxDepth(1)]
    private ?User $adminValidatorA = null;

    #[ORM\ManyToOne]
    #[Groups([
        'fights:get',
        'admin:get'
    ])]
    #[MaxDepth(1)]
    private ?User $adminValidatorB = null;

    #[ORM\OneToMany(mappedBy: 'fight', targetEntity: Bet::class)]
    #[Groups([
        'admin:get',
        'admin:post'
    ])]
    private Collection $bets;

    #[Groups([
        'admin:get',
        'admin:post',
    ])]
    private array $odds;

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

    public function getFighterA(): ?Fighter
    {
        return $this->fighterA;
    }

    public function setFighterA(?Fighter $fighterA): self
    {
        $this->fighterA = $fighterA;

        return $this;
    }

    public function getFighterB(): ?Fighter
    {
        return $this->fighterB;
    }

    public function setFighterB(?Fighter $fighterB): self
    {
        $this->fighterB = $fighterB;

        return $this;
    }

    public function getWinner(): ?Fighter
    {
        return $this->winner;
    }

    public function setWinner(?Fighter $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function getLoser(): ?Fighter
    {
        return $this->loser;
    }

    public function setLoser(?Fighter $loser): self
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

    public function getRatingFighterA(): ?User
    {
        return $this->ratingFighterA;
    }

    public function setRatingFighterA(?User $ratingFighterA): self
    {
        $this->ratingFighterA = $ratingFighterA;

        return $this;
    }

    public function getRatingFighterB(): ?User
    {
        return $this->ratingFighterB;
    }

    public function setRatingFighterB(?User $ratingFighterB): self
    {
        $this->ratingFighterB = $ratingFighterB;

        return $this;
    }

    public function getRatingNullMatch(): ?User
    {
        return $this->ratingNullMatch;
    }

    public function setRatingNullMatch(?User $ratingNullMatch): self
    {
        $this->ratingNullMatch = $ratingNullMatch;

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

    public function getOdds(): array
    {
        return (new FightOddsService($this))->odd();
    }
}
