<?php

namespace App\Controller\Cart;

use App\Entity\Cart;
use App\Entity\CartItem;

use App\Repository\CartRepository;
use App\Repository\CartItemRepository;

use App\Service\Checkout\CheckoutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CartCheckout extends AbstractController
{
    public function __construct(
        private readonly CheckoutService $checkout,
        private readonly CartRepository $cartRepository,
        private readonly Security $security,
    ) {
    }

    public function __invoke(Request $request, Cart $cart): Cart
    {
        $params = json_decode($request->getContent());
        $cart = $this->cartRepository->find($params->id);

        if (!$cart) {
            throw new BadRequestException("Cart not found");
        }

        $user = $this->security->getUser();

        $this->checkout->checkout($user, $cart);

        return $cart;
    }
}
