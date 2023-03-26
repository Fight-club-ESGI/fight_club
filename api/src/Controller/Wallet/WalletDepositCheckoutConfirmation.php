<?php

namespace App\Controller\Wallet;

use App\Entity\WalletTransaction;
use App\Enum\WalletTransaction\WalletTransactionStatusEnum;
use App\Enum\WalletTransaction\WalletTransactionTypeEnum;
use App\Repository\UserRepository;
use App\Repository\WalletTransactionRepository;
use App\Service\Checkout\CheckoutService;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WalletDepositCheckoutConfirmation extends AbstractController
{
    private CheckoutService $checkout;

    public function __construct(CheckoutService $checkoutService) {
        $this->checkout = $checkoutService;
    }

    public function __invoke(Request $request, Security $security, string $wallet_transaction_id, WalletTransactionRepository $walletTransactionRepository): void
    {
        $walletTransaction = $walletTransactionRepository->find($wallet_transaction_id);

        //todo add confirmation
        //$this->checkout->confirmation($walletTransaction);
    }
}
