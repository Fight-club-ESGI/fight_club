<?php

namespace App\Controller\Cart;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Repository\CartRepository;
use App\Service\Checkout\CheckoutService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CartCheckout extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CheckoutService $checkoutService,
        private readonly Security $security,
    ) {
    }

    public function __invoke(Request $request, Cart $cart): Cart
    {
        $params = json_decode($request->getContent());
        $type = $params->type;

        in_array($type, ['wallet', 'stripe']) || throw $this->createNotFoundException('Type not found');

        if (!$cart instanceof Cart) {
            throw $this->createNotFoundException('Cart not found');
        }

        $totalItems = $cart->getCartItems()->reduce(fn (int $carry, CartItem $item) => $carry + $item->getQuantity(), 0);
        $totalPrice = $cart->getCartItems()->reduce(fn (int $carry, CartItem $item) => $carry + ($item->getTicketEvent()->getPrice() * $item->getQuantity()), 0);

        dd($totalItems, $totalPrice, $cart->getUser()->getWallet()->getAmount());

        switch ($type) {
            case 'wallet':
                $this->checkoutService->checkoutWallet($cart);
                break;
            case 'stripe':
                $this->checkoutService->checkoutStripe($cart);
                break;
        }

        return $cart;
    }
}
