<?php

namespace App\Enums;

enum OrderStatus: int
{
    case STATUS_PENDING = 0;
    case STATUS_PAID = 1;
    case STATUS_FULFILLED = 2;
    case STATUS_REFUND = 3;
}