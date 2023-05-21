<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\Cart\CartCheckoutStripe;
use App\Controller\Cart\CartCheckoutWallet;
use App\Entity\Trait\EntityIdTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: CartRepository::class)]
#[ApiResource(
    operations: [
        new Patch(
            inputFormats: [
                'multipart' => ['multipart/form-data'],
                'json' => ['application/json']
            ],
            denormalizationContext: ['groups' => ['cart:patch']],
            security: 'is_granted("ROLE_USER") and object.getUser() === user'
        ),
        new Get(
            normalizationContext: ['groups' => ['cart:get']],
            security: 'is_granted("ROLE_USER") and object.getUser() === user'
        ),
        new Get(
            uriTemplate: '/my-cart',
            normalizationContext: ['groups' => ['cart:get']],
            security: 'is_granted("ROLE_USER") and object.getUser() === user',
            securityMessage: 'You need to be connected',
            read: true,
            name: 'self_cart'
        ),
        new Post(
            uriTemplate: '/carts/{id}/checkout/stripe',
            controller: CartCheckoutStripe::class,
            read: false,
            name: 'cart_checkout',
        ),
        new Post(
            uriTemplate: '/carts/{id}/checkout/wallet',
            controller: CartCheckoutWallet::class,
            read: false,
            name: 'cart_checkout',
        )
    ]
)]
class Cart
{
    use EntityIdTrait;
    use TimestampableTrait;

    #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)]
    private ?User $_user = null;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartItem::class)]
    #[MaxDepth(1)]
    #[Groups([
        'cart:patch',
        'cart:get',
    ])]
    private Collection $cartItems;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }

    public function getUser(): ?User
    {
        return $this->_user;
    }

    public function setUser(User $_user): self
    {
        $this->_user = $_user;

        return $this;
    }

    /**
     * @return Collection<int, CartItem>
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): self
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems->add($cartItem);
            $cartItem->setCart($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): self
    {
        if ($this->cartItems->removeElement($cartItem)) {
            // set the owning side to null (unless already changed)
            if ($cartItem->getCart() === $this) {
                $cartItem->setCart(null);
            }
        }

        return $this;
    }

    public function getTotalPrice(): float
    {
        $total = 0;

        foreach ($this->cartItems as $cartItem) {
            $total += $cartItem->getTotalPrice() * $cartItem->getQuantity();
        }

        return $total;
    }
}
