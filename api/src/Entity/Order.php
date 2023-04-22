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

    #[ORM\OneToOne(inversedBy: 'orders', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    #[ORM\Column(length: 255)]
    private ?OrderStatusEnum $status = OrderStatusEnum::PENDING;

    #[ORM\Column(length: 255, nullable: true)]
    private ?OrderPaymentTypeEnum $paymentType = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $stripe = null;

    #[ORM\OneToMany(mappedBy: '_order', targetEntity: Ticket::class)]
    private Collection $tickets;

    #[ORM\Column]
    private ?float $price = null;

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
}
