<?php

namespace App\Entity\Trait;

use ApiPlatform\Metadata\ApiProperty;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Bridge\Doctrine\Types\UuidType;

trait EntityIdTrait
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator("doctrine.uuid_generator")]
    #[Groups(['additional:get'])]
    private ?string $id = null;

    public function getId(): ?string
    {
        return $this->id;
    }
}
