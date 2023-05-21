<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\Bet\BetCreateDirectPayment;
use App\Controller\Bet\BetCreateWalletPayment;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Enum\Bet\BetStatusEnum;
use App\Repository\BetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: BetRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['bet:get']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => []]
        ),
        new Post(
            uriTemplate: 'bets/payment/direct',
            controller: BetCreateDirectPayment::class,
            normalizationContext: ['groups' => ['bet:get']],
            denormalizationContext: ['groups' => ['bet:post']]
        ),
        new Post(
            uriTemplate: 'bets/payment/wallet',
            controller: BetCreateWalletPayment::class,
            normalizationContext: ['groups' => ['bet:get']],
            denormalizationContext: ['groups' => ['bet:post']]
        ),
        new GetCollection(
            uriTemplate: 'bet',
            paginationMaximumItemsPerPage: 10,
            security: "is_granted('ROLE_USER')",
            name: "self_bet"
        )
    ]
)]
#[ApiFilter(
    OrderFilter::class, properties: ['createdAt'], arguments: ['orderParameterName' => 'order']
)]
#[ApiFilter(
    SearchFilter::class, properties: ['status' => 'exact']
)]
class Bet
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\ManyToOne(inversedBy: 'bets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'admin:get',
        'admin:post',
        'bet:get',
        'bet:post',
    ])]
    #[MaxDepth(1)]
    private ?Fight $fight = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'admin:get',
        'admin:post',
        'bet:get',
        'bet:post',
    ])]
    #[MaxDepth(1)]
    private ?Fighter $betOn = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'admin:get',
        'bet:get',
    ])]
    #[MaxDepth(1)]
    private ?User $bettor = null;

    #[ORM\Column]
    #[Groups([
        'admin:get',
        'admin:post',
        'bet:get',
        'bet:post',
    ])]
    private ?int $amount = 0;

    #[ORM\Column(length: 255)]
    #[Groups([
        'admin:get',
        'bet:get',
    ])]
    private ?BetStatusEnum $status = BetStatusEnum::PENDING;

    #[ORM\OneToOne(inversedBy: 'bet', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?WalletTransaction $wallet_transaction = null;

    public function getFight(): ?Fight
    {
        return $this->fight;
    }

    public function setFight(?Fight $fight): self
    {
        $this->fight = $fight;

        return $this;
    }

    public function getBetOn(): ?Fighter
    {
        return $this->betOn;
    }

    public function setBetOn(?Fighter $betOn): self
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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
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

    public function getWalletTransaction(): ?WalletTransaction
    {
        return $this->wallet_transaction;
    }

    public function setWalletTransaction(?WalletTransaction $wallet_transaction): self
    {
        $this->wallet_transaction = $wallet_transaction;

        return $this;
    }
}
