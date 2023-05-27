<?php

namespace App\Enum\WalletTransaction;

enum WalletTransactionTypeEnum: string
{
    case DEPOSIT = "deposit";
    case WITHDRAWAL = "withdrawal";
    case BET = "bet";
    case WALLET_PAYMENT = "wallet_payment";
    case STRIPE_PAYMENT = "stripe_payment";
    case REFUND = "refund";
}
