<?php

namespace App\Util\Interfaces;

use App\Entity\Core\Business;

interface BusinessInterface
{
    public function getBusiness(): ?Business;
}