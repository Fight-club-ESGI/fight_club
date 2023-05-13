<?php

namespace App\Dto;

use App\Entity\FightCategory;
use Symfony\Component\Validator\Constraints as Assert;

final class EventDto
{
    private ?FightCategory $fightCategory = null;
    private string $name = '';
    private ?string $location = null;
    private ?string $locationLink = null;
    private ?\DateTimeInterface $timeStart = null;
    private ?\DateTimeInterface $timeEnd = null;
    private ?string $description = null;
    private ?int $capacity = null;
    private ?bool $vip = false;
}