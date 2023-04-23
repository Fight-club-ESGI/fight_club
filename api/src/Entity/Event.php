<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\Event\EventTickets;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Entity\Trait\VichUploadTrait;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            normalizationContext: ['groups' => ['events:get']],
        ),
        new Post(
            normalizationContext: ['groups' => ['tickets:get', 'additional:get']],
            denormalizationContext: ['groups' => ['tickets:post']],
            security: 'is_granted("ROLE_ADMIN")',
        ),
        new Delete(),
        new Put(),
        new GetCollection(
            normalizationContext: ['groups' => ['tickets:get', 'additional:get']],
            read: false,
            name: 'event_tickets'
        )
    ]
)]
class Event
{
    use EntityIdTrait;
    use VichUploadTrait;
    use TimestampableTrait;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[Groups([
        'admin:get',
        'tickets:get',
        'events:get'
    ])]
    private ?FightCategory $fightCategory = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'admin:get',
        'tickets:get',
        'tickets:post',
        'events:get'
    ])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups([
        'admin:get',
        'tickets:get',
        'tickets:post',
        'events:get'
    ])]
    private ?string $location = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([
        'admin:get',
        'tickets:get',
        'tickets:post',
        'events:get'
    ])]
    private ?string $location_link = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups([
        'admin:get',
        'tickets:get',
        'tickets:post',
        'events:get'
    ])]
    private ?\DateTimeInterface $time_start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups([
        'admin:get',
        'tickets:get',
        'tickets:post',
        'events:get'
    ])]
    private ?\DateTimeInterface $time_end = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([
        'admin:get',
        'tickets:get',
        'tickets:post',
        'events:get'
    ])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups([
        'admin:get',
        'tickets:get',
        'tickets:post',
        'events:get'
    ])]
    private ?int $capacity = null;

    #[ORM\Column]
    #[Groups([
        'admin:get',
        'tickets:get',
        'tickets:post',
        'events:get'
    ])]
    private ?bool $vip = false;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Ticket::class)]
    #[Groups([
        'admin:get',
        'tickets:get'
    ])]
    private Collection $tickets;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Fight::class, orphanRemoval: true)]
    #[Groups([
        'admin:get',
        'tickets:get'
    ])]
    private Collection $fights;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: TicketEvent::class)]
    #[Groups([
        'admin:get',
        'tickets:get',
        'ticket:category:post',
        'events:get'
    ])]
    private Collection $ticket_events;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->fights = new ArrayCollection();
        $this->ticket_events = new ArrayCollection();
    }

    public function getFightCategory(): ?FightCategory
    {
        return $this->fightCategory;
    }

    public function setFightCategory(?FightCategory $fightCategory): self
    {
        $this->fightCategory = $fightCategory;

        return $this;
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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getLocationLink(): ?string
    {
        return $this->location_link;
    }

    public function setLocationLink(?string $location_link): self
    {
        $this->location_link = $location_link;

        return $this;
    }

    public function getTimeStart(): ?\DateTimeInterface
    {
        return $this->time_start;
    }

    public function setTimeStart(?\DateTimeInterface $time_start): self
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(?int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function isVip(): ?bool
    {
        return $this->vip;
    }

    public function setVip(bool $vip): self
    {
        $this->vip = $vip;

        return $this;
    }

    /**
     * @return Collection<int, Fight>
     */
    public function getFights(): Collection
    {
        return $this->fights;
    }

    public function addFight(Fight $fight): self
    {
        if (!$this->fights->contains($fight)) {
            $this->fights->add($fight);
            $fight->setEvent($this);
        }

        return $this;
    }

    public function removeFight(Fight $fight): self
    {
        if ($this->fights->removeElement($fight)) {
            // set the owning side to null (unless already changed)
            if ($fight->getEvent() === $this) {
                $fight->setEvent(null);
            }
        }

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
            $ticketEvent->setEvent($this);
        }

        return $this;
    }

    public function removeTicketEvent(TicketEvent $ticketEvent): self
    {
        if ($this->ticket_events->removeElement($ticketEvent)) {
            // set the owning side to null (unless already changed)
            if ($ticketEvent->getEvent() === $this) {
                $ticketEvent->setEvent(null);
            }
        }

        return $this;
    }
}
