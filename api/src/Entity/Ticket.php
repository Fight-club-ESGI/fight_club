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
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['admin:get', 'tickets:get'])]
    private ?TicketEvent $ticket_event = null;

    #[ORM\Column]
    #[Groups(['admin:get', 'tickets:get'])]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['admin:get', 'tickets:get'])]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['admin:get', 'tickets:get'])]
    private ?Order $_order = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['admin:get', 'tickets:get'])]
    private ?TicketCategory $ticket_category = null;

    #[ORM\Column(length: 255)]
    #[Groups(['admin:get', 'tickets:get'])]
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

    public function setReference(string|null $reference = null): self
    {
        if (is_null($this->reference) && is_null($reference)) {
            $this->reference = sprintf('T-%s-%s', date('Ymd'), bin2hex(random_bytes(3)));
        } else {
            $this->reference = $reference;
        }

        return $this;
    }
}
