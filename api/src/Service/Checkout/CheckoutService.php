<?php

namespace App\Service\Checkout;

use App\Entity\User;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

class CheckoutService
{
    private StripeClient $stripe;

    public function __construct() {
        $this->stripe = new StripeClient($_ENV['STRIPE_KEY']);
    }

    public function checkout(float $amount, array $params = [], int $quantity = 1, bool $default_confirmation_url = true): Session
    {

        $amount = number_format($amount, 2, '.', '');

        #todo change url
        $params['line_items'] = [];
        $params['line_items'][0]['price_data']['currency'] = 'eur';
        $params['line_items'][0]['price_data']['product_data']['name'] = 'transaction name';
        $params['line_items'][0]['price_data']['unit_amount'] = intval($amount * 100);
        $params['line_items'][0]['quantity'] = $quantity;
        $params['mode'] = 'payment';

        if ($default_confirmation_url) {
            $confirmationUrl = 'https://localhost';
            $params['success_url'] = $confirmationUrl;
            $params['cancel_url'] = $confirmationUrl;
        }

        $params['expires_at'] = time() + 1800; # 30 min
        $params['payment_method_types'][] = "card";

        return $this->stripe->checkout->sessions->create($params);
    }

    public function getStripe(): StripeClient
    {
        return $this->stripe;
    }
}
