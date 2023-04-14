<?php

namespace App\Enum\Order;


enum OrderPaymentTypeEnum: string
{
    case MASTERCARD = "mastercard";
    case PAYPAL = "paypal";
    case STRIPE = "stripe";
}
