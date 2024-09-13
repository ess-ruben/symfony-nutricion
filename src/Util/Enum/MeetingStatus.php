<?php

namespace App\Util\Enum;

class MeetingStatus
{
    public const PENDING = 0;
    public const COMPLETE = 1;
    public const REJECTED = 2;
    public const EXPIRED = 3;

    public const VALUES = [
        'Pendiente' => self::PENDING,
        'Completado' => self::COMPLETE,
        'Rechazado' => self::REJECTED,
        'Expirado' => self::EXPIRED
    ];
}