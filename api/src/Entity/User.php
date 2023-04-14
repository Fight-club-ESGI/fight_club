<?php

namespace App\Entity;

use App\Controller\User\CheckTokenValidityController;
use Carbon\Carbon;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\User\Role\ChangeUserRole;
use App\Controller\User\UserMe;
use App\Controller\User\ResetPasswordController;
use App\Controller\User\ValidateResetPasswordController;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Entity\Trait\VichUploadTrait;
use App\Repository\UserRepository;
use App\State\UserPasswordHasher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new Patch(
            uriTemplate: '/users/{user}/role',
            controller: ChangeUserRole::class,
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'You need to be an admin',
            read: false,
            name: 'users_change_role'
        ),
        new Post(
            inputFormats: [
                'multipart' => ['multipart/form-data'],
                'json' => ['application/json']
            ],
            processor: UserPasswordHasher::class,
        ),
        new Get(
            normalizationContext: ['groups' => ['user:get']],
            security: "is_granted('ROLE_USER')"
        ),
        new Get(
            uriTemplate: 'me',
            controller: UserMe::class,
            normalizationContext: ['groups' => ['user:self']],
            security: "is_granted('ROLE_USER')",
            securityMessage: 'You need to be connected',
            read: false,
            name: 'me'
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['user:get_collection']],
        ),
        new Patch(
            normalizationContext: ['groups' => ['user:get']],
            denormalizationContext: ['groups' => ['user:patch']],
            security: 'is_granted("ROLE_ADMIN") or object === user',
            securityMessage: 'You are not allowed to update this user',
            processor: UserPasswordHasher::class,
        ),
        new Post(
            uriTemplate: '/reset_password',
            controller: ResetPasswordController::class,
            name: 'reset-password'
        ),
        new Post(
            uriTemplate: '/validate_reset_password',
            controller: ValidateResetPasswordController::class,
            name: 'validate-reset-password'
        ),
        new Get(
            uriTemplate: '/check_token_validity/{token}',
            controller: CheckTokenValidityController::class,
            name: 'check-token-validity'
        )
    ]
)]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use EntityIdTrait;
    use VichUploadTrait;
    use TimestampableTrait;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups([
        'user:get',
        'user:post',
        'user:get_collection',
        'user:patch',
        'user:self',
    ])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups([
        'user:get',
        'user:get_collection',
        'admin:post',
        'admin:patch',
        'user:self'
    ])]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['admin:get'])]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?Carbon $tokenDate = null;

    #[ORM\OneToOne(mappedBy: 'users', cascade: ['persist', 'remove'])]
    #[Groups(['admin:get', 'user:self'])]
    private ?Wallet $wallet = null;

    #[ORM\OneToMany(mappedBy: 'sponsor', targetEntity: Sponsorship::class)]
    #[Groups(['admin:get', 'user:self'])]
    private Collection $sponsorshipsAsSponsor;

    #[ORM\OneToOne(mappedBy: 'sponsored', targetEntity: Sponsorship::class)]
    #[Groups(['admin:get', 'user:self'])]
    private ?Sponsorship $sponsorshipsAsSponsored;

    #[ORM\OneToOne(mappedBy: 'customer', cascade: ['persist', 'remove'])]
    #[Groups(['admin:get', 'user:self'])]
    private ?Order $orders = null;

    public function __construct()
    {
        $this->sponsorshipsAsSponsor = new ArrayCollection();
        //$this->fights = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(Wallet $wallet): self
    {
        // set the owning side of the relation if necessary
        if ($wallet->getUsers() !== $this) {
            $wallet->setUsers($this);
        }

        $this->wallet = $wallet;

        return $this;
    }

    /**
     * @return Collection<int, Sponsorship>
     */
    public function getSponsorshipsAsSponsor(): Collection
    {
        return $this->sponsorshipsAsSponsor;
    }

    public function addSponsorshipsAsSponsor(Sponsorship $sponsorshipsAsSponsor): self
    {
        if (!$this->sponsorshipsAsSponsor->contains($sponsorshipsAsSponsor)) {
            $this->sponsorshipsAsSponsor->add($sponsorshipsAsSponsor);
            $sponsorshipsAsSponsor->setSponsor($this);
        }

        return $this;
    }

    public function removeSponsorshipsAsSponsor(Sponsorship $sponsorshipsAsSponsor): self
    {
        if ($this->sponsorshipsAsSponsor->removeElement($sponsorshipsAsSponsor)) {
            // set the owning side to null (unless already changed)
            if ($sponsorshipsAsSponsor->getSponsor() === $this) {
                $sponsorshipsAsSponsor->setSponsor(null);
            }
        }

        return $this;
    }

    /**
     * @return Sponsorship
     */
    public function getSponsorshipsAsSponsored(): ?Sponsorship
    {
        return $this->sponsorshipsAsSponsored;
    }

    public function addSponsorshipsAsSponsored(Sponsorship $sponsorshipsAsSponsored): self
    {
        if (!$this->sponsorshipsAsSponsored->contains($sponsorshipsAsSponsored)) {
            $this->sponsorshipsAsSponsored->add($sponsorshipsAsSponsored);
            $sponsorshipsAsSponsored->setSponsor($this);
        }

        return $this;
    }

    public function removeSponsorshipsAsSponsored(Sponsorship $sponsorshipsAsSponsored): self
    {
        if ($this->sponsorshipsAsSponsored->removeElement($sponsorshipsAsSponsored)) {
            // set the owning side to null (unless already changed)
            if ($sponsorshipsAsSponsored->getSponsor() === $this) {
                $sponsorshipsAsSponsored->setSponsor(null);
            }
        }

        return $this;
    }

    public function getOrders(): ?Order
    {
        return $this->orders;
    }

    public function setOrders(Order $orders): self
    {
        // set the owning side of the relation if necessary
        if ($orders->getCustomer() !== $this) {
            $orders->setCustomer($this);
        }

        $this->orders = $orders;

        return $this;
    }

    /**
     * @return Collection<int, Fight>
     */
    /*
    public function getFights(): Collection
    {
        return $this->fights;
    }*/

    /**
     * @return Carbon | null
     */
    public function getTokenDate(): ?Carbon
    {
        return $this->tokenDate;
    }

    /**
     * @param Carbon $tokenDate
     * @return self
     */
    public function setTokenDate(Carbon $tokenDate): self
    {
        $this->tokenDate = $tokenDate;
        return $this;
    }
}
