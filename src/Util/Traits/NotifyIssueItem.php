<?php

namespace App\Util\Traits;
use App\Util\Enum\NotifyType;

trait NotifyIssueItem
{

    public function getNotifyType(): string
    {
        return NotifyType::ISSUE_TYPE;
    }
}