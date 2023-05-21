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

class WalletCartCheckoutConfirmation extends AbstractController
{
    public function __construct(
        private readonly CheckoutService $checkoutService,
        private readonly Security $security,
    ) {
    }

    public function __invoke(Request $request, WalletTransaction $wallet_transaction): WalletTransaction
    {
        $user = $this->security->getUser();

        $this->checkoutService->confirmation($wallet_transaction);

        if ($wallet_transaction->getStatus() === WalletTransactionStatusEnum::ACCEPTED) {
            $order = $wallet_transaction->getOrder();
            [$createdTickets, $refundTickets] = $this->checkoutService->generateTickets($order, $order->getPendingTickets());

            if (count($refundTickets) > 0) {
                $refundPrice = array_reduce($refundTickets, fn (int $carry, $item) => $carry + $item['ticketEvent']->getPrice() * $item['quantity'], 0);

                $refundWalletTransaction = (new WalletTransaction)
                    ->setWallet($user->getWallet());

                $this->checkoutService->refund($refundWalletTransaction, $refundPrice);
            }
        }

        return $wallet_transaction;
    }
}
