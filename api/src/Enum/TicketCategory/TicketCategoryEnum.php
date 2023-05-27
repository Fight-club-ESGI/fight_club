<?php

namespace App\Enum\TicketCategory;


enum TicketCategoryEnum: string
{
    case GOLD = "GOLD";
    case SILVER = "SILVER";
    case V_VIP = "V_VIP";
    case VIP = "VIP";
    case PEUPLE = "PEUPLE";
}
