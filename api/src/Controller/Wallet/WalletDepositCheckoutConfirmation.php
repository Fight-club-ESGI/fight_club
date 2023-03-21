<?php

namespace App\Controller;

use App\Entity\WalletTransaction;
use App\Enum\WalletTransactionStatusType;
use App\Enum\WalletTransactionTypeType;
use App\Repository\UserRepository;
use App\Repository\WalletTransactionRepository;
use App\Service\CheckoutService;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class WalletDepositCheckoutConfirmation extends AbstractController
{
    private CheckoutService $checkout;

    public function __construct(CheckoutService $checkoutService) {
        $this->checkout = $checkoutService;
    }

    public function __invoke(Request $request, Security $security, string $wallet_transaction_id, WalletTransactionRepository $walletTransactionRepository): void
    {
        $walletTransaction = $walletTransactionRepository->find($wallet_transaction_id);

        $this->checkout->confirmation($walletTransaction);
    }
}
