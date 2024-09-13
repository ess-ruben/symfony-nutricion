<?php

namespace App\Util\Traits;
use App\Util\Enum\NotifyType;

trait NotifyBusinessItem
{

    public function getNotifyType(): string
    {
        return NotifyType::BUSINESS_TYPE;
    }
}