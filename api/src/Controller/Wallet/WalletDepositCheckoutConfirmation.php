<?php

namespace App\Controller\Wallet;

use App\Entity\WalletTransaction;
use App\Enum\WalletTransaction\WalletTransactionStatusEnum;
use App\Enum\WalletTransaction\WalletTransactionTypeEnum;
use App\Repository\UserRepository;
use App\Repository\WalletTransactionRepository;
use App\Service\Checkout\CheckoutService;
use Nette\Utils\Json;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WalletDepositCheckoutConfirmation extends AbstractController
{
    public function __construct(
        private readonly CheckoutService $checkout,
    ) {
    }

    public function __invoke(Request $request, WalletTransaction $wallet_transaction): WalletTransaction
    {

        $this->checkout->confirmation($wallet_transaction);

        return $wallet_transaction;
    }
}
