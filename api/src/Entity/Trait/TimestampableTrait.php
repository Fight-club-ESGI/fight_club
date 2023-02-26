<?php

namespace App\Entity\Trait;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\Doctrine\CarbonDoctrineType;
use Carbon\Doctrine\CarbonImmutableType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

trait TimestampableTrait
{
    #[Groups("timestamp")]
    #[Gedmo\Timestampable(on: "create")]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ["default" => "CURRENT_TIMESTAMP"])]
    private CarbonImmutable $createdAt;

    #[Groups("timestamp")]
    #[Gedmo\Timestampable(on: "update")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ["default" => "CURRENT_TIMESTAMP"])]
    private Carbon $updatedAt;

    /**
     * @return CarbonImmutable
     */
    public function getCreatedAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param CarbonImmutable $createdAt
     * @return self
     */
    public function setCreatedAt(CarbonImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    /**
     * @param Carbon $updatedAt
     * @return self
     */
    public function setUpdatedAt(Carbon $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
