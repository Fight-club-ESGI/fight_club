<?php

namespace App\Service\Cart;

use App\Entity\Cart;
use App\Entity\WalletTransaction;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;

class CartService
{

    private StripeClient $stripe;
    private WalletTransaction $walletTransaction;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function checkCart(Cart $cart)
    {
        $this->stripe = new StripeClient($_ENV['STRIPE_KEY']);

        $params['line_items'] = [];
        $params['line_items'][0]['price_data']['currency'] = 'eur';
        $params['line_items'][0]['price_data']['product_data']['name'] = 'transaction name';
        $params['line_items'][0]['price_data']['unit_amount'] = intval($cart->getTotalPrice() * 100);
        $params['line_items'][0]['quantity'] = 1;
        $params['mode'] = 'payment';

        $confirmationUrl = $_ENV['FRONT_URL'] . "/checkout/confirmation?transaction_id=" . $this->walletTransaction->getId();
        $params['success_url'] = $confirmationUrl;
        $params['cancel_url'] = $confirmationUrl;

        $params['expires_at'] = time() + 1800; # 30 min
        $params['payment_method_types'][] = "card";

        $checkout_session = $this->stripe->checkout->sessions->create($params);

        $this->walletTransaction->setStripeRef($checkout_session->id);
        $this->entityManager->persist($this->walletTransaction);
        $this->entityManager->flush();

        return $checkout_session;
    }
}
