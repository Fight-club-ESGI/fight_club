<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\SponsorshipRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SponsorshipRepository::class)]
#[ApiResource]
class Sponsorship
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\ManyToOne(inversedBy: 'sponsorshipsAsSponsor')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sponsor = null;

    #[ORM\OneToOne(inversedBy: 'sponsorshipsAsSponsored', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sponsored = null;

    #[ORM\Column]
    private ?bool $email_validation = null;

    #[ORM\Column]
    private ?bool $sponsor_validation = null;

    public function getSponsor(): ?User
    {
        return $this->sponsor;
    }

    public function setSponsor(?User $sponsor): self
    {
        $this->sponsor = $sponsor;

        return $this;
    }

    public function getSponsored(): ?User
    {
        return $this->sponsored;
    }

    public function setSponsored(User $sponsored): self
    {
        $this->sponsored = $sponsored;

        return $this;
    }

    public function isEmailValidation(): ?bool
    {
        return $this->email_validation;
    }

    public function setEmailValidation(bool $email_validation): self
    {
        $this->email_validation = $email_validation;

        return $this;
    }

    public function isSponsorValidation(): ?bool
    {
        return $this->sponsor_validation;
    }

    public function setSponsorValidation(bool $sponsor_validation): self
    {
        $this->sponsor_validation = $sponsor_validation;

        return $this;
    }
}
