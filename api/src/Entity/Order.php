<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Enum\Order\OrderPaymentTypeEnum;
use App\Enum\Order\OrderStatusEnum;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ApiResource]
class Order
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\OneToOne(inversedBy: 'orders', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    #[ORM\Column(length: 255)]
    private ?OrderStatusEnum $status = OrderStatusEnum::PENDING;

    #[ORM\Column(length: 255, nullable: true)]
    private ?OrderPaymentTypeEnum $paymentType = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $stripe = null;

    #[ORM\ManyToMany(targetEntity: Ticket::class, inversedBy: 'orders')]
    private Collection $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(User $customer): self
    {
        $this->customer = $customer;

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

    public function getPaymentType(): ?OrderPaymentTypeEnum
    {
        return $this->paymentType;
    }

    public function setPaymentType(OrderPaymentTypeEnum $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getStripe(): ?string
    {
        return $this->stripe;
    }

    public function setStripe(?string $stripe): self
    {
        $this->stripe = $stripe;

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
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        $this->tickets->removeElement($ticket);

        return $this;
    }
}
