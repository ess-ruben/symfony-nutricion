<?php

namespace App\Util\Traits;
use App\Util\Enum\NotifyType;

trait NotifyUserItem
{

    public function getNotifyType(): string
    {
        return NotifyType::USER_TYPE;
    }
}