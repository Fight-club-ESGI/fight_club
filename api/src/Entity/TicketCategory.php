<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\TicketCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketCategoryRepository::class)]
#[ORM\Table(name: '`ticket_category`')]
#[ApiResource]
class TicketCategory
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'float')]
    private ?float $price = 0;

    #[ORM\Column(type: 'integer')]
    private ?int $max_quantity = 0;

    #[ORM\OneToMany(mappedBy: 'ticket_category', targetEntity: TicketEvent::class)]
    private Collection $ticket_events;

    #[ORM\OneToMany(mappedBy: 'ticket_category', targetEntity: Ticket::class)]
    private Collection $tickets;

    public function __construct()
    {
        $this->ticket_events = new ArrayCollection();
        $this->tickets = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getMaxQuantity(): ?int
    {
        return $this->max_quantity;
    }

    public function setMaxQuantity(int $max_quantity): self
    {
        $this->max_quantity = $max_quantity;

        return $this;
    }

    /**
     * @return Collection<int, TicketEvent>
     */
    public function getTicketEvents(): Collection
    {
        return $this->ticket_events;
    }

    public function addTicketEvent(TicketEvent $ticketEvent): self
    {
        if (!$this->ticket_events->contains($ticketEvent)) {
            $this->ticket_events->add($ticketEvent);
            $ticketEvent->setTicketCategory($this);
        }

        return $this;
    }

    public function removeTicketEvent(TicketEvent $ticketEvent): self
    {
        if ($this->ticket_events->removeElement($ticketEvent)) {
            // set the owning side to null (unless already changed)
            if ($ticketEvent->getTicketCategory() === $this) {
                $ticketEvent->setTicketCategory(null);
            }
        }

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
            $ticket->setTicketCategory($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getTicketCategory() === $this) {
                $ticket->setTicketCategory(null);
            }
        }

        return $this;
    }
}
