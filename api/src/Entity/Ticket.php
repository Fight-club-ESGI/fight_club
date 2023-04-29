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
use Symfony\Component\Serializer\Annotation\MaxDepth;

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
            denormalizationContext: ['groups' => ['tickets:post']],
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
    #[Groups([
        'admin:get',
        'tickets:get',
        'tickets:post'
    ])]
    #[MaxDepth(1)]
    private ?TicketEvent $ticketEvent = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'admin:get',
        'tickets:get'
    ])]
    #[MaxDepth(1)]
    private ?Order $_order = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'admin:get',
        'tickets:get',
        'tickets:post'
    ])]
    private ?string $reference = null;

    public function __construct() {
        $this->setReference();
    }

    public function getTicketEvent(): ?TicketEvent
    {
        return $this->ticketEvent;
    }

    public function setTicketEvent(?TicketEvent $ticketEvent): self
    {
        $this->ticketEvent = $ticketEvent;

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
