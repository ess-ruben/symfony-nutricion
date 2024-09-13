<?php

namespace App\Util\Traits;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Core\Business;

trait BusinessItem
{

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Business $business = null;

    public function getBusiness(): ?Business
    {
        return $this->business;
    }

    public function setBusiness(?Business $business): self
    {
        $this->business = $business;

        return $this;
    }
}