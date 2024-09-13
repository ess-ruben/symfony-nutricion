<?php

namespace App\Util\Traits;

use Doctrine\ORM\Mapping as ORM;

trait ActiveItem
{

    #[ORM\Column(type: 'boolean', nullable: true, options: ['default' => 1])]
    private ?bool $isActive = true;

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $active): self
    {
        $this->isActive = $active;

        return $this;
    }
}