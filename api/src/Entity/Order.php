<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Enum\Order\OrderPaymentTypeEnum;
use App\Enum\Order\OrderStatusEnum;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['order:get']],
            security: "is_granted('ROLE_ADMIN') or object.getCustomer() === user",
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['order:get_collection']],
            security: "is_granted('ROLE_ADMIN') or object.getCustomer() === user",
        ),
        new Post(
            inputFormats: [
                'multipart' => ['multipart/form-data'],
                'json' => ['application/json']
            ],
            normalizationContext: ['groups' => ['order:post']],
            security: "is_granted('ROLE_ADMIN') or object.getCustomer() === user",
        ),
        new Put(
            inputFormats: [
                'multipart' => ['multipart/form-data'],
                'json' => ['application/json']
            ],
            normalizationContext: ['groups' => ['order:put']],
            security: "is_granted('ROLE_ADMIN') or object.getCustomer() === user",
        ),
        new Delete(
            normalizationContext: ['groups' => ['order:delete']],
            security: "is_granted('ROLE_ADMIN')",
        ),
    ]
)]
class Order
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $_user = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'admin:get',
        'admin:post',
        'order:post'
    ])]
    private ?OrderStatusEnum $status = OrderStatusEnum::PENDING;

    #[ORM\OneToMany(mappedBy: '_order', targetEntity: Ticket::class)]
    #[Groups([
        'admin:get'
    ])]
    #[MaxDepth(1)]
    private Collection $tickets;

    #[ORM\Column]
    #[Groups([
        'admin:get',
        'admin:post'
    ])]
    private ?float $price = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[MaxDepth(1)]
    private ?WalletTransaction $walletTransaction = null;

    #[ORM\OneToMany(mappedBy: '_order', targetEntity: PendingTicket::class)]
    #[MaxDepth(1)]
    private Collection $pendingTickets;

    #[ORM\Column(length: 255)]
    #[Groups([
        'order:get'
    ])]
    private ?string $reference = null;

    public function __construct()
    {
        $this->setReference();
        $this->tickets = new ArrayCollection();
        $this->pendingTickets = new ArrayCollection();
    }

    public function getUser(): ?User
    {
        return $this->_user;
    }

    public function setUser(?User $_user): self
    {
        $this->_user = $_user;

        return $this;
    }

    public function getStatus(): ?OrderStatusEnum
    {
        return $this->status;
    }

    public function setStatus(OrderStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setOrder($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getOrder() === $this) {
                $ticket->setOrder(null);
            }
        }

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getWalletTransaction(): ?WalletTransaction
    {
        return $this->walletTransaction;
    }

    public function setWalletTransaction(?WalletTransaction $walletTransaction): self
    {
        $this->walletTransaction = $walletTransaction;

        return $this;
    }

    /**
     * @return Collection<int, PendingTicket>
     */
    public function getPendingTickets(): Collection
    {
        return $this->pendingTickets;
    }

    public function addPendingTicket(PendingTicket $pendingTicket): self
    {
        if (!$this->pendingTickets->contains($pendingTicket)) {
            $this->pendingTickets->add($pendingTicket);
            $pendingTicket->setOrder($this);
        }

        return $this;
    }

    public function removePendingTicket(PendingTicket $pendingTicket): self
    {
        if ($this->pendingTickets->removeElement($pendingTicket)) {
            // set the owning side to null (unless already changed)
            if ($pendingTicket->getOrder() === $this) {
                $pendingTicket->setOrder(null);
            }
        }

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string|null $reference = null): self
    {
        if (is_null($this->reference) && is_null($reference)) {
            $this->reference = sprintf('T-%s-%s', date('Ymd'), strtoupper(bin2hex(random_bytes(3))));
        } else {
            $this->reference = $reference;
        }

        return $this;
    }
}
