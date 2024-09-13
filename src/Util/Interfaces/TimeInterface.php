<?php

namespace App\Util\Interfaces;

interface TimeInterface
{   
    public function getCreateAt():?\DateTimeInterface;
    public function getUpdatedAt():?\DateTimeInterface;
    public function getDeletedAt(): ?\DateTimeInterface;
}