<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\TicketEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Faker\Core\Number;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: TicketEventRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            normalizationContext: ['groups' => ['ticket:event:get', 'ticket:category:get']],
            denormalizationContext: ['groups' => ['ticket:event:post']],
            security: 'is_granted("ROLE_ADMIN")',
        ),
        new Get(
            normalizationContext: ['groups' => ['ticket:event:get']],
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['ticket:event:get_collection']],
        )
    ]
)]
class TicketEvent
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\ManyToOne(inversedBy: 'ticketEvents')]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)]
    #[Groups([
        'admin:get',
        'admin:post',
        'ticket:event:get',
        'ticket:event:post'
    ])]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'ticketEvents')]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)]
    #[Groups([
        'admin:get',
        'admin:post',
        'ticket:event:post',
        'ticket:event:get',
        'event:ticket:get'
    ])]
    private ?TicketCategory $ticketCategory = null;

    #[ORM\Column]
    #[Groups([
        'admin:get',
        'admin:post',
        'ticket:event:post',
        'ticket:event:get',
        'event:ticket:get'
    ])]
    private ?int $maxQuantity = null;

    #[ORM\OneToMany(mappedBy: 'ticketEvent', targetEntity: Ticket::class)]
    #[MaxDepth(1)]
    #[Groups([
        'admin:get',
        'ticket:event:get_collection',
        'event:ticket:get'
    ])]
    private Collection $tickets;

    #[ORM\Column]
    #[Groups([
        'admin:get',
        'admin:post',
        'ticket:event:post',
        'ticket:event:get',
        'event:ticket:get'
    ])]
    private ?float $price = null;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
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
        return $this->ticketCategory;
    }

    public function setTicketCategory(?TicketCategory $ticketCategory): self
    {
        $this->ticketCategory = $ticketCategory;

        return $this;
    }

    public function getMaxQuantity(): ?int
    {
        return $this->maxQuantity;
    }

    public function setMaxQuantity(int $maxQuantity): self
    {
        $this->maxQuantity = $maxQuantity;

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
