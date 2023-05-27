<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\Fighter\CreateFighter;
use App\Controller\Fighter\DeleteFighter;
use App\Controller\Fighter\GetFighter;
use App\Controller\Fighter\UpdateFighter;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Entity\Trait\VichUploadTrait;
use App\Enum\Fight\FighterGenderEnum;
use App\Repository\FighterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: FighterRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ["groups" => ['fighter:get', 'fights:get']],
            name: "get_fighters",
        ),
        new Get(
            controller: GetFighter::class,
            normalizationContext: ["groups" => ['fighter:get', 'fights:get']],
        ),
        new Post(
            controller: CreateFighter::class,
            security: "is_granted('ROLE_ADMIN')",
            deserialize: false
        ),
        new Patch(
            inputFormats: [
                'multipart' => ['multipart/form-data'],
                'json' => ['application/json']
            ],
            controller: UpdateFighter::class,
            security: "is_granted('ROLE_ADMIN')",
            deserialize: false
        ),
        new Delete(
            controller: DeleteFighter::class,
            security: "is_granted('ROLE_ADMIN')"
        )
    ]
)]
#[ApiFilter(
    SearchFilter::class, properties: ['isActive' => 'exact']
)]
#[Vich\Uploadable]
class Fighter
{
    use EntityIdTrait;
    use VichUploadTrait;
    use TimestampableTrait;

    #[ORM\Column(length: 255)]
    #[Groups([
        'admin:get',
        'admin:post',
        'fighter:get',
        'events:get',
        'fighter:post'
    ])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'admin:get',
        'admin:post',
        'fighter:get',
        'fighter:post'
    ])]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([
        'admin:get',
        'admin:post',
        'fighter:get',
        'fighter:post'
    ])]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column]
    #[Groups([
        'admin:get',
        'admin:post',
        'fighter:get',
        'fighter:post'
    ])]
    private ?int $height = null;

    #[ORM\Column]
    #[Groups([
        'admin:get',
        'admin:post',
        'fighter:get',
        'fighter:post'
    ])]
    private ?int $weight = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'admin:get',
        'admin:post',
        'fighter:get',
        'fighter:post'
    ])]
    private ?string $nationality = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'admin:get',
        'admin:post',
        'fighter:get',
        'fighter:post'
    ])]
    private ?FighterGenderEnum $gender = FighterGenderEnum::FEMALE;

    #[ORM\ManyToOne(inversedBy: 'fighters')]
    #[Groups([
        'admin:get',
        'admin:post'
    ])]
    #[MaxDepth(1)]
    private ?FightCategory $fightCategory = null;

    #[ORM\OneToMany(mappedBy: 'winner', targetEntity: Fight::class)]
    #[Groups([
        'admin:get'
    ])]
    #[MaxDepth(1)]
    private Collection $winners;

    #[ORM\OneToMany(mappedBy: 'loser', targetEntity: Fight::class)]
    #[Groups([
        'admin:get'
    ])]
    #[MaxDepth(1)]
    private Collection $losers;

    #[ORM\OneToMany(mappedBy: 'fighterA', targetEntity: Fight::class)]
    #[Groups([
        'admin:get',
        'fighter:get'
    ])]
    #[MaxDepth(1)]
    private Collection $fightsA;

    #[ORM\OneToMany(mappedBy: 'fighterB', targetEntity: Fight::class)]
    #[Groups([
        'admin:get',
        'fighter:get'
    ])]
    #[MaxDepth(1)]
    private Collection $fightsB;

    #[ORM\ManyToMany(targetEntity: Fight::class, inversedBy: 'fighters')]
    #[MaxDepth(3)]
    #[Groups([
        'admin:get',
        'admin:post',
        'fighter:get',
    ])]
    private Collection $fights;

    #[ORM\Column]
    #[Groups([
        'admin:get',
        'admin:post',
        'fighter:get',
    ])]
    private ?bool $isActive = true;

    public function __construct()
    {
        $this->fights = new ArrayCollection();
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getGender(): ?FighterGenderEnum
    {
        return $this->gender;
    }

    public function setGender(FighterGenderEnum $gender): self
    {
        $this->gender = $gender;

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
        }

        return $this;
    }

    public function removeFight(Fight $fight): self
    {
        $this->fights->removeElement($fight);

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
