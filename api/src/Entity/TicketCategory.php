<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\TicketCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TicketCategoryRepository::class)]
#[ORM\Table(name: '`ticket_category`')]
#[ApiResource(
    operations: [
        new Post(
            denormalizationContext: ['groups' => ['ticket:category:post']],
        ),
        new Get(
            normalizationContext: ['groups' => ['ticket:category:get']],
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['ticket:category:get']],
        )
    ]
)]
class TicketCategory
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\Column(length: 255)]
    #[Groups([
        'ticket:category:get',
        'ticket:category:post'
    ])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'ticket_category', targetEntity: TicketEvent::class)]
    #[Groups([
        'ticket:category:get',
        'ticket:category:post'
    ])]
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
