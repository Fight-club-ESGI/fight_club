<?php

namespace App\Enum\Bet;

enum BetStatusEnum: string
{
    case PENDING = 'pending';
    case LOSE = 'lose';
    case WIN = 'win';
}
