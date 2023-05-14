<?php

namespace App\Controller\Cart;

use ApiPlatform\OpenApi\Model\Response;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Repository\CartRepository;
use App\Service\Checkout\CheckoutService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;

class CartCheckout extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CheckoutService $checkoutService,
        private readonly CartRepository $cartRepository,
    ) {
    }

    public function __invoke(Request $request, Cart $cart): Cart
    {
        $params = json_decode($request->getContent());
        $type = $params->type;

        if (!$cart instanceof Cart) {
            throw $this->createNotFoundException('Cart not found');
        }

        return $cart;

        in_array($type, ['wallet', 'stripe']) || throw $this->createNotFoundException('Type not found');
    }
}
