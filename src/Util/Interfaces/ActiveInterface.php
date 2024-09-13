<?php

namespace App\Util\Interfaces;

interface ActiveInterface
{
    public function getIsActive():?bool;
    public function setIsActive(bool $active):self;
}