<?php

namespace App\Enum\WalletTransaction;

enum WalletTransactionPaymentTypeEnum: string
{
    case MASTERCARD = "mastercard";
    case PAYPAL = "paypal";
}
