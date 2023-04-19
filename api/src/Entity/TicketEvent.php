<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\TicketEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketEventRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            normalizationContext: ['groups' => ['event:get']],
            denormalizationContext: ['groups' => ['event:post']],
        )
    ]
)]
class TicketEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ticket_events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'ticket_events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TicketCategory $ticket_category = null;

    #[ORM\Column]
    private ?int $max_quantity = null;

    #[ORM\OneToMany(mappedBy: 'ticket_event', targetEntity: Ticket::class)]
    private Collection $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTicketCategory(): ?TicketCategory
    {
        return $this->ticket_category;
    }

    public function setTicketCategory(?TicketCategory $ticket_category): self
    {
        $this->ticket_category = $ticket_category;

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
            $ticket->setTicketEvent($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getTicketEvent() === $this) {
                $ticket->setTicketEvent(null);
            }
        }

        return $this;
    }
}
