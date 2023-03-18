<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Enum\WalletTransaction\WalletTransactionStatusEnum;
use App\Enum\WalletTransaction\WalletTransactionTypeEnum;
use App\Repository\WalletTransactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletTransactionRepository::class)]
#[ApiResource]
class WalletTransaction
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\ManyToOne(inversedBy: 'walletTransactions')]
    private ?Wallet $wallet = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 255, enumType: WalletTransactionStatusEnum::class)]
    private ?WalletTransactionStatusEnum $status = null;

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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
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
