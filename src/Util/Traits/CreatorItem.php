<?php

namespace App\Util\Traits;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Core\User;

trait CreatorItem
{

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    protected ?User $creator = null;

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getForceCreator():bool
    {
        return true;
    }
}