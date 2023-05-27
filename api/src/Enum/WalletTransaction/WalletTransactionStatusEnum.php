<?php

namespace App\Enum\WalletTransaction;

enum WalletTransactionStatusEnum: string
{
    case ACCEPTED = "accepted";
    case PENDING = "pending";
    case REJECTED = "rejected";
    case CANCELLED = "cancelled";
}
