<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FightCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FightCategoryRepository::class)]
#[ApiResource]
class FightCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'fightCategory', targetEntity: WeightCategory::class, orphanRemoval: true)]
    private Collection $weightCategories;

    public function __construct()
    {
        $this->weightCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
}