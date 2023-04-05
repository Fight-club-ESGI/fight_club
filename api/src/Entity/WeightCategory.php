<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\WeightCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeightCategoryRepository::class)]
#[ORM\Table(name: '`weight_category`')]
#[ApiResource]
class WeightCategory
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\Column]
    private ?int $min_weight = null;

    #[ORM\Column]
    private ?int $max_weight = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'weightCategories')]
    #[ORM\JoinColumn(nullable: true)]
    private ?FightCategory $fightCategory = null;

    public function getMinWeight(): ?int
    {
        return $this->min_weight;
    }

    public function setMinWeight(int $min_weight): self
    {
        $this->min_weight = $min_weight;

        return $this;
    }

    public function getMaxWeight(): ?int
    {
        return $this->max_weight;
    }

    public function setMaxWeight(int $max_weight): self
    {
        $this->max_weight = $max_weight;

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

    public function getFightCategory(): ?FightCategory
    {
        return $this->fightCategory;
    }

    public function setFightCategory(?FightCategory $fightCategory): self
    {
        $this->fightCategory = $fightCategory;

        return $this;
    }
}
