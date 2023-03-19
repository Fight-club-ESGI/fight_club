<?php

namespace App\Enum\Order;


enum OrderStatusEnum: string
{
    case FAIL = "fail";
    case PENDING = "pending";
    case SUCCESS = "success";
}
