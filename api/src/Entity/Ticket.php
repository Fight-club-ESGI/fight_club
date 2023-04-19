<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\TicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['tickets:get']],
            read: true
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['tickets:get']],
            read: true
        ),
        new Post(
            normalizationContext: ['groups' => ['tickets:get']],
            read: true
        )
    ]
)]
class Ticket
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[Groups(['admin:get', 'tickets:get'])]
    private ?TicketCategory $ticketCategory = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[Groups(['admin:get', 'tickets:get'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\Column]
    #[Groups(['admin:get', 'tickets:get'])]
    private ?float $price = 0;

    #[ORM\Column]
    #[Groups(['admin:get', 'tickets:get'])]
    private ?bool $availability = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['admin:get', 'tickets:get'])]
    private ?\DateTimeInterface $time_start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['admin:get', 'tickets:get'])]
    private ?\DateTimeInterface $time_end = null;

    #[ORM\ManyToMany(targetEntity: Order::class, mappedBy: 'tickets')]
    #[Groups(['admin:get', 'tickets:get'])]
    private Collection $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getTicketCategory(): ?TicketCategory
    {
        return $this->ticketCategory;
    }

    public function setTicketCategory(?TicketCategory $ticketCategory): self
    {
        $this->ticketCategory = $ticketCategory;

        return $this;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function isAvailability(): ?bool
    {
        return $this->availability;
    }

    public function setAvailability(bool $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    public function getTimeStart(): ?\DateTimeInterface
    {
        return $this->time_start;
    }

    public function setTimeStart(\DateTimeInterface $time_start): self
    {
        $this->time_start = $time_start;

        return $this;
    }

    public function getTimeEnd(): ?\DateTimeInterface
    {
        return $this->time_end;
    }

    public function setTimeEnd(?\DateTimeInterface $time_end): self
    {
        $this->time_end = $time_end;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->addTicket($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            $order->removeTicket($this);
        }

        return $this;
    }
}
