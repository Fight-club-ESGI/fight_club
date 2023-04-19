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
        new Post(
            denormalizationContext: ['groups' => ['ticket:post']],
            read: false
        ),
        new Get(
            normalizationContext: ['groups' => ['ticket:get']],
            security: 'is_granted("ROLE_ADMIN") || object._order.user == user',
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['ticket:get:collection']],
        )
    ]
)]
class Ticket
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TicketEvent $ticket_event = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'ticket:get',
        'ticket:post'
    ])]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $_order = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TicketCategory $ticket_category = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    public function getTicketEvent(): ?TicketEvent
    {
        return $this->ticket_event;
    }

    public function setTicketEvent(?TicketEvent $ticket_event): self
    {
        $this->ticket_event = $ticket_event;

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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->_order;
    }

    public function setOrder(?Order $_order): self
    {
        $this->_order = $_order;

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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }
}
