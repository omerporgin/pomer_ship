<?php

namespace App\Enum;

/**
 * just trying...
 */
enum Payment:int
{
    case WAITING = 0;
    case PAID = 1;
    case CANCELLED = 2;
}
