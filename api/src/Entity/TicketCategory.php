<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Enum\TicketCategory\TicketCategoryEnum;
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
            normalizationContext: ['groups' => ['ticket:category:get']],
            denormalizationContext: ['groups' => ['ticket:category:post']],
            security: 'is_granted("ROLE_ADMIN")',
        ),
        new Get(
            normalizationContext: ['groups' => ['ticket:category:get']],
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['ticket:category:get_collection']],
        )
    ]
)]
class TicketCategory
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\Column(length: 255)]
    #[Groups([
        'admin:get',
        'ticket:category:post',
        'event:ticket:get',
        'ticket:category:get',
        'ticket:category:get_collection'
    ])]
    private ?TicketCategoryEnum $name = TicketCategoryEnum::PEUPLE;

    #[ORM\OneToMany(mappedBy: 'ticketCategory', targetEntity: TicketEvent::class)]
    #[Groups([
        'admin:get',
        'tickets:get',
    ])]
    private Collection $ticketEvents;

    public function __construct()
    {
        $this->ticketEvents = new ArrayCollection();
    }

    public function getName(): ?TicketCategoryEnum
    {
        return $this->name;
    }

    public function setName(TicketCategoryEnum $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, TicketEvent>
     */
    public function getTicketEvents(): Collection
    {
        return $this->ticketEvents;
    }

    public function addTicketEvent(TicketEvent $ticketEvent): self
    {
        if (!$this->ticketEvents->contains($ticketEvent)) {
            $this->ticketEvents->add($ticketEvent);
            $ticketEvent->setTicketCategory($this);
        }

        return $this;
    }

    public function removeTicketEvent(TicketEvent $ticketEvent): self
    {
        if ($this->ticketEvents->removeElement($ticketEvent)) {
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
}
