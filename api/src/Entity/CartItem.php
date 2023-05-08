<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            security: '(is_granted("ROLE_USER") and object.cart._user == user) or is_granted("ROLE_ADMIN")',
            denormalizationContext: ['groups' => ['cart:item:post']],
        ),
        new Get(
            security: '(is_granted("ROLE_USER") and object.cart._user == user) or is_granted("ROLE_ADMIN")',
            normalizationContext: ['groups' => ['cart:item:get']],
        )
    ]
)]
class CartItem
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)]
    #[Groups([
        'cart:item:post',
        'cart:item:get'
    ])]
    private ?Cart $cart = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)]
    #[Groups([
        'cart:item:post',
        'cart:item:get'
    ])]
    private ?TicketEvent $ticketEvent = null;

    #[ORM\Column]
    #[MaxDepth(1)]
    #[Groups([
        'cart:item:post',
        'cart:item:get'
    ])]
    private ?int $quantity = null;

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getTicketEvent(): ?TicketEvent
    {
        return $this->ticketEvent;
    }

    public function setTicketEvent(?TicketEvent $ticketEvent): self
    {
        $this->ticketEvent = $ticketEvent;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
