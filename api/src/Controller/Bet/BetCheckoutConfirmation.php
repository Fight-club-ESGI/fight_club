<?php

namespace App\Controller\Bet;

use App\Entity\WalletTransaction;
use App\Service\Checkout\CheckoutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BetCheckoutConfirmation extends AbstractController
{
    public function __construct(
        private readonly CheckoutService $checkout,
    ) {
    }

    public function __invoke(Request $request, WalletTransaction $wallet_transaction): WalletTransaction
    {

        $this->checkout->betConfirmation($wallet_transaction);

        return $wallet_transaction;
    }
}
