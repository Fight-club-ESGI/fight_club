<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\Wallet\WalletDepositCheckoutConfirmation;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Enum\WalletTransaction\WalletTransactionStatusEnum;
use App\Enum\WalletTransaction\WalletTransactionTypeEnum;
use App\Repository\WalletTransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WalletTransactionRepository::class)]
#[ORM\Table(name: '`wallet_transaction`')]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: "/wallet_transaction/{id}/confirmation",
            controller: WalletDepositCheckoutConfirmation::class,
            normalizationContext: ['groups' => ['wallet_transaction:get']],
            security: "is_granted('ROLE_USER')",
            name: 'wallet_transaction_confirmation'
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['wallet_transaction:get_collection']],
        ),
    ]
)]
class WalletTransaction
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\ManyToOne(inversedBy: 'walletTransactions')]
    private ?Wallet $wallet = null;

    #[ORM\Column]
    #[Groups([
        'user:self',
        'admin:get',
        'wallet_transaction:get',
        'wallet_transaction:get_collection'
    ])]
    private ?int $amount = null;

    #[ORM\Column(length: 255, enumType: WalletTransactionStatusEnum::class)]
    #[Groups([
        'user:self',
        'admin:get',
        'wallet_transaction:get',
        'wallet_transaction:get_collection',
    ])]
    private ?WalletTransactionStatusEnum $status = WalletTransactionStatusEnum::PENDING;

    #[ORM\Column(length: 255, enumType: WalletTransactionTypeEnum::class)]
    private ?WalletTransactionTypeEnum $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $transaction = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripe_ref = null;

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): self
    {
        $this->wallet = $wallet;

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

    public function getStatus(): ?WalletTransactionStatusEnum
    {
        return $this->status;
    }

    public function setStatus(WalletTransactionStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getType(): ?WalletTransactionTypeEnum
    {
        return $this->type;
    }

    public function setType(WalletTransactionTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTransaction(): ?string
    {
        return $this->transaction;
    }

    public function setTransaction(string $transaction): self
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function getStripeRef(): ?string
    {
        return $this->stripe_ref;
    }

    public function setStripeRef(string $stripe_ref): self
    {
        $this->stripe_ref = $stripe_ref;

        return $this;
    }
}
