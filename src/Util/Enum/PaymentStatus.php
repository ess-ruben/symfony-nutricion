<?php

namespace App\Util\Enum;

class PaymentStatus
{
    public const PREPARE = 0;
    public const PENDING = 1;
    public const CONFIRM = 2;
    public const COMPLETE = 3;
    public const REJECTED = 4;
    public const EXPIRED = 5;
}