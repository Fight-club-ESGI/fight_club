<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\WeightCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WeightCategoryRepository::class)]
#[ORM\Table(name: '`weight_category`')]
#[ApiResource(
    operations: [
        new Post(
            normalizationContext: ['groups' => ['wallet:category:get']]
        )
    ]
)]
class WeightCategory
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\Column]
    #[Groups([
        'admin:get',
        'wallet:category:get'
    ])]
    private ?int $min_weight = null;

    #[ORM\Column]
    #[Groups([
        'admin:get',
        'wallet:category:get'
    ])]
    private ?int $max_weight = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'admin:get',
        'wallet:category:get'
    ])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'weightCategories')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups([
        'admin:get',
        'wallet:category:get'
    ])]
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
