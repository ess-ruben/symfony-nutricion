<?php

namespace App\Util\Traits;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Core\User;

trait UserForceItem
{

    #[ORM\ManyToOne()]
    protected User $user;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getForceUser():bool
    {
        return true;
    }
}