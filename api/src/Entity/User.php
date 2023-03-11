<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\User\Role\ChangeUserRole;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Entity\Trait\VichUploadTrait;
use App\Repository\UserRepository;
use App\State\UserPasswordHasher;
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
        'user:patch'
    ])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups([
        'user:get',
        'user:get_collection',
        'admin:user:post',
        'admin:user:patch'
    ])]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['super_admin:user:get'])]
    private ?string $password = null;

    #[ORM\OneToOne(mappedBy: 'users', cascade: ['persist', 'remove'])]
    private ?Wallet $wallet = null;

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
}
