<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\SponsorshipRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SponsorshipRepository::class)]
#[ApiResource]
class Sponsorship
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\ManyToOne(inversedBy: 'sponsorshipsAsSponsor')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'admin:get',
        'admin:post'
    ])]
    private ?User $sponsor = null;

    #[ORM\OneToOne(inversedBy: 'sponsorshipsAsSponsored')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'admin:get',
        'admin:post'
    ])]
    private ?User $sponsored = null;

    #[ORM\Column]
    #[Groups([
        'admin:get',
        'admin:post'
    ])]
    private ?bool $emailValidation = false;

    #[ORM\Column]
    #[Groups([
        'admin:get',
    ])]
    private ?bool $sponsorValidation = false;

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
        return $this->emailValidation;
    }

    public function setEmailValidation(bool $emailValidation): self
    {
        $this->emailValidation = $emailValidation;

        return $this;
    }

    public function isSponsorValidation(): ?bool
    {
        return $this->sponsorValidation;
    }

    public function setSponsorValidation(bool $sponsorValidation): self
    {
        $this->sponsorValidation = $sponsorValidation;

        return $this;
    }
}
