<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\WalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['user:get']],
            security: "is_granted('ROLE_ADMIN')",
        )
    ]
)]
class Wallet
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\Column]
    #[Groups(['admin:get', 'user:self'])]
    private ?float $amount = 0.00;

    #[ORM\OneToOne(inversedBy: 'wallet', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $users = null;

    #[ORM\OneToMany(mappedBy: 'wallet', targetEntity: WalletTransaction::class)]
    private Collection $walletTransactions;

    public function __construct()
    {
        $this->walletTransactions = new ArrayCollection();
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
