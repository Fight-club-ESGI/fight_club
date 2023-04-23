<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\FightCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FightCategoryRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            normalizationContext: ["groups" => []],
            denormalizationContext: ["groups" => []],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Get(
            normalizationContext: ["groups" => ["fight:category:get"]]
        )
    ]
)]
class FightCategory
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\Column(length: 255)]
    #[Groups([
        'admin:get',
        'admin:post',
    ])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'fightCategory', targetEntity: WeightCategory::class, orphanRemoval: true)]
    #[Groups([
        'admin:get',
    ])]
    private Collection $weightCategories;

    #[ORM\OneToMany(mappedBy: 'fightCategory', targetEntity: Fighter::class)]
    #[Groups([
        'admin:get',
    ])]
    private Collection $fighters;

    #[ORM\OneToMany(mappedBy: 'fightCategory', targetEntity: Event::class)]
    #[Groups([
        'admin:get',
    ])]
    private Collection $events;

    public function __construct()
    {
        $this->weightCategories = new ArrayCollection();
        $this->fighters = new ArrayCollection();
        $this->events = new ArrayCollection();
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
     * @return Collection<int, WeightCategory>
     */
    public function getWeightCategories(): Collection
    {
        return $this->weightCategories;
    }

    public function addWeightCategory(WeightCategory $weightCategory): self
    {
        if (!$this->weightCategories->contains($weightCategory)) {
            $this->weightCategories->add($weightCategory);
            $weightCategory->setFightCategory($this);
        }

        return $this;
    }

    public function removeWeightCategory(WeightCategory $weightCategory): self
    {
        if ($this->weightCategories->removeElement($weightCategory)) {
            // set the owning side to null (unless already changed)
            if ($weightCategory->getFightCategory() === $this) {
                $weightCategory->setFightCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fighter>
     */
    public function getFighters(): Collection
    {
        return $this->fighters;
    }

    public function addFighter(Fighter $fighter): self
    {
        if (!$this->fighters->contains($fighter)) {
            $this->fighters->add($fighter);
            $fighter->setFightCategory($this);
        }

        return $this;
    }

    public function removeFighter(Fighter $fighter): self
    {
        if ($this->fighters->removeElement($fighter)) {
            // set the owning side to null (unless already changed)
            if ($fighter->getFightCategory() === $this) {
                $fighter->setFightCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setFightCategory($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getFightCategory() === $this) {
                $event->setFightCategory(null);
            }
        }

        return $this;
    }
}
