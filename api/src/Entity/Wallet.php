<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Controller\Wallet\WalletDepositCheckout;
use App\Controller\Wallet\WalletWithdraw;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\WalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
#[ORM\Table(name: '`wallet`')]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/wallet',
            normalizationContext: ['groups' => ['user:get']],
            security: 'is_granted("ROLE_USER")',
            read: true,
            name: 'self_user_wallet'
        ),
        new Get(
            normalizationContext: ['groups' => ['user:get']],
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Post(
            uriTemplate: "/wallet/deposit",
            controller: WalletDepositCheckout::class,
            security: "is_granted('ROLE_USER')",
            read: false,
            name: 'wallet_deposit_checkout'
        ),
        new Post(
            uriTemplate: "/wallet/withdraw",
            controller: WalletWithdraw::class,
            normalizationContext: ['groups' => ['wallet:get']],
            security: "is_granted('ROLE_USER')",
            name: 'wallet_withdraw_checkout'
        )
    ]
)]
class Wallet
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\Column]
    #[Groups([
        'admin:get',
        'user:self'
    ])]
    private ?int $amount = 0;

    #[ORM\OneToOne(inversedBy: 'wallet', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'admin:get',
        'user:self'
    ])]
    #[MaxDepth(1)]
    private ?User $users = null;

    #[ORM\OneToMany(mappedBy: 'wallet', targetEntity: WalletTransaction::class)]
    #[Groups([
        'admin:get',
        'user:self'
    ])]
    private Collection $walletTransactions;

    public function __construct()
    {
        $this->walletTransactions = new ArrayCollection();
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

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(User $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection<int, WalletTransaction>
     */
    public function getWalletTransactions(): Collection
    {
        return $this->walletTransactions;
    }

    public function addWalletTransaction(WalletTransaction $walletTransaction): self
    {
        if (!$this->walletTransactions->contains($walletTransaction)) {
            $this->walletTransactions->add($walletTransaction);
            $walletTransaction->setWallet($this);
        }

        return $this;
    }

    public function removeWalletTransaction(WalletTransaction $walletTransaction): self
    {
        if ($this->walletTransactions->removeElement($walletTransaction)) {
            // set the owning side to null (unless already changed)
            if ($walletTransaction->getWallet() === $this) {
                $walletTransaction->setWallet(null);
            }
        }

        return $this;
    }
}
