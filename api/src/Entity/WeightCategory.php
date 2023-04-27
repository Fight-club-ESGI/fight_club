<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
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
        new GetCollection(),
        new Get(),
        new Post(
            normalizationContext: ['groups' => ['wallet:category:get']],
            denormalizationContext: ['groups' => []],
            security: "is_granted('ROLE_ADMIN')"
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
        'admin:post',
        'wallet:category:get'
    ])]
    private ?int $minWeight = null;

    #[ORM\Column]
    #[Groups([
        'admin:get',
        'admin:post',
        'wallet:category:get'
    ])]
    private ?int $maxWeight = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'admin:get',
        'admin:post',
        'wallet:category:get'
    ])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'weightCategories')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups([
        'admin:get',
        'admin:post',
        'wallet:category:get'
    ])]
    private ?FightCategory $fightCategory = null;

    public function getMinWeight(): ?int
    {
        return $this->minWeight;
    }

    public function setMinWeight(int $minWeight): self
    {
        $this->minWeight = $minWeight;

        return $this;
    }

    public function getMaxWeight(): ?int
    {
        return $this->maxWeight;
    }

    public function setMaxWeight(int $maxWeight): self
    {
        $this->maxWeight = $maxWeight;

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
