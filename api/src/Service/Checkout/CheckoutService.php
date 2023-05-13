<?php

namespace App\Service\Checkout;

use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\WalletTransaction;
use App\Enum\WalletTransaction\WalletTransactionStatusEnum;
use App\Enum\WalletTransaction\WalletTransactionTypeEnum;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

class CheckoutService
{
    private StripeClient $stripe;
    private WalletTransaction $walletTransaction;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->stripe = new StripeClient($_ENV['STRIPE_KEY']);
    }

    public function checkout(User $user, float $amount, WalletTransactionTypeEnum $type, array $params = [], int $quantity = 1, bool $default_confirmation_url = true): Session
    {
        $this->walletTransaction = $this->recordWalletTransaction($user->getWallet(), $amount, WalletTransactionStatusEnum::PENDING, $type);

        #todo change url
        $params['line_items'] = [];
        $params['line_items'][0]['price_data']['currency'] = 'eur';
        $params['line_items'][0]['price_data']['product_data']['name'] = 'transaction name';
        $params['line_items'][0]['price_data']['unit_amount'] = intval($amount);
        $params['line_items'][0]['quantity'] = $quantity;
        $params['mode'] = 'payment';

        if ($default_confirmation_url) {
            $confirmationUrl = $_ENV['FRONT_URL'] . "/checkout/confirmation?transaction_id=" . $this->walletTransaction->getId();
            $params['success_url'] = $confirmationUrl;
            $params['cancel_url'] = $confirmationUrl;
        }

        $params['expires_at'] = time() + 1800; # 30 min
        $params['payment_method_types'][] = "card";

        $checkout_session = $this->stripe->checkout->sessions->create($params);

        $this->walletTransaction->setStripeRef($checkout_session->id);
        $this->entityManager->persist($this->walletTransaction);
        $this->entityManager->flush();

        return $checkout_session;
    }

    public function recordWalletTransaction(Wallet $wallet, float $amount, WalletTransactionStatusEnum $status, WalletTransactionTypeEnum $type): WalletTransaction
    {
        $walletTransaction = new WalletTransaction();

        $walletTransaction->setWallet($wallet);
        $walletTransaction->setAmount($amount);
        $walletTransaction->setStatus($status);
        $walletTransaction->setType($type);

        $this->entityManager->persist($walletTransaction);
        $this->entityManager->flush();

        return $walletTransaction;
    }

    public function confirmation(WalletTransaction $walletTransaction): void
    {
        if ($walletTransaction->getStripeRef() !== null) {
            $transaction = $this->stripe->checkout->sessions->retrieve($walletTransaction->getStripeRef(), []);

            switch ($walletTransaction->getStatus()) {
                case WalletTransactionStatusEnum::PENDING:
                    if ($transaction->payment_status === 'paid') {
                        $walletTransaction->setStatus(WalletTransactionStatusEnum::ACCEPTED);
                        $walletTransaction->getWallet()->setAmount($walletTransaction->getWallet()->getAmount() + floatval($transaction->amount_total / 100));
                    } else {
                        $walletTransaction->setStatus(WalletTransactionStatusEnum::REJECTED);
                    }

                    $this->entityManager->persist($walletTransaction);
                    $this->entityManager->flush();
                    break;
                case WalletTransactionStatusEnum::REJECTED:
                case WalletTransactionStatusEnum::ACCEPTED:
                case WalletTransactionStatusEnum::CANCELLED:
                    break;
            }
        }
    }

<<<<<<< HEAD
    public function refund(WalletTransaction $walletTransaction, $amount): void
    {
        $walletTransaction->setStatus(WalletTransactionStatusEnum::ACCEPTED);
        $walletTransaction->setType(WalletTransactionTypeEnum::REFUND);
        $walletTransaction->getWallet()->setAmount($walletTransaction->getWallet()->getAmount() + $amount);

        $this->entityManager->persist($walletTransaction);
        $this->entityManager->flush();
    }

=======
<<<<<<< HEAD
>>>>>>> 74d71ed (Timeout cart)
=======
>>>>>>> a55c75f4462c39ad481b5ebc3386c4ba0d16eabf
>>>>>>> ca1cbefd0f7b21f9fa4a04b2247d3751a5ebec63
    public function orderConfirmation(WalletTransaction $walletTransaction): void
    {
        if ($walletTransaction->getStripeRef() !== null) {
            $transaction = $this->stripe->checkout->sessions->retrieve($walletTransaction->getStripeRef(), []);

            switch ($walletTransaction->getStatus()) {
                case WalletTransactionStatusEnum::PENDING:
                    if ($transaction->payment_status === 'paid') {
                        $walletTransaction->setStatus(WalletTransactionStatusEnum::ACCEPTED);
                        $walletTransaction->getWallet()->setAmount($walletTransaction->getWallet()->getAmount() + floatval($transaction->amount_total / 100));
                    } else {
                        $walletTransaction->setStatus(WalletTransactionStatusEnum::REJECTED);
                    }

                    $this->entityManager->persist($walletTransaction);
                    $this->entityManager->flush();
                    break;
                case WalletTransactionStatusEnum::REJECTED:
                case WalletTransactionStatusEnum::ACCEPTED:
                case WalletTransactionStatusEnum::CANCELLED:
                    break;
            }
        }
    }

    public function getStripe(): StripeClient
    {
        return $this->stripe;
    }

    public function getWalletTransaction(): WalletTransaction
    {
        return $this->walletTransaction;
    }
}
