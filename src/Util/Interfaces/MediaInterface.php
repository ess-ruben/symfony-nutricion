<?php

namespace App\Util\Interfaces;

interface MediaInterface
{
    public function getSerializerFields():array;
    public function setCompleteUrl(?string $contentUrl,string $field);
}