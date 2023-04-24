<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\Sponsorship\AcceptedRequest;
use App\Controller\Sponsorship\AcceptRequest;
use App\Controller\Sponsorship\PendingRequest;
use App\Controller\Sponsorship\RemoveSponsor;
use App\Controller\Sponsorship\SendInvitation;
use App\Controller\Sponsorship\SentRequest;
use App\Controller\Sponsorship\ValidateVIP;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\SponsorshipRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SponsorshipRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(),
        new Get(
            uriTemplate: '/sponsorships/{sponsorshipId}/accept-request',
            controller: AcceptRequest::class,
            normalizationContext: ['groups' => ['sponsor:get']],
            security: 'is_granted("ROLE_ADMIN")',
            read: false
        ),
        new Delete(
            uriTemplate: '/sponsorships/requests/remove/{sponsorshipId}',
            controller: RemoveSponsor::class,
            normalizationContext: ['groups' => ['sponsor:get']],
            security: 'is_granted("ROLE_ADMIN")',
            read: false
        ),
        new Post(
            uriTemplate: '/sponsorships/send-invitation',
            controller: SendInvitation::class,
            normalizationContext: ['groups' => ['sponsor:get']],
            denormalizationContext: ['groups' => ['sponsor:post']],
            security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_VVIP")',
            read: false
        ),
        new Get(
            uriTemplate: '/sponsorships/{token}/validate-email',
            controller: ValidateVIP::class,
            normalizationContext: ['groups' => ['sponsor:get']],
            read: false
        ),
        new Get(
            uriTemplate: '/sponsorships/requests/pending',
            controller: PendingRequest::class,
            normalizationContext: ['groups' => ['sponsor:get', 'user:get']],
            security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_VVIP")',
            read: false
        ),
        new Get(
            uriTemplate: '/sponsorships/requests/sent',
            controller: SentRequest::class,
            normalizationContext: ['groups' => ['sponsor:get', 'user:get']],
            read: false
        ),
        new Get(
            uriTemplate: '/sponsorships/requests/accepted',
            controller: AcceptedRequest::class,
            normalizationContext: ['groups' => ['sponsor:get', 'user:get']],
            security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_VVIP")',
            read: false
        )
    ]
)]
class Sponsorship
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\ManyToOne(inversedBy: 'sponsorshipsAsSponsor')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'admin:get',
        'admin:post',
        'sponsor:get',
        'sponsor:post'
    ])]
    private ?User $sponsor = null;

    #[ORM\OneToOne(inversedBy: 'sponsorshipsAsSponsored')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'admin:get',
        'admin:post',
        'sponsor:post'
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
