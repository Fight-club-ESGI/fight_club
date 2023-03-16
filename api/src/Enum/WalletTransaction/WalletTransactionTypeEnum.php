<?php

namespace App\Enum\WalletTransaction;

enum WalletTransactionTypeEnum: string
{
    case DEPOSIT = "deposit";
    case WITHDRAWAL = "withdrawal";
    case BET = "bet";
}