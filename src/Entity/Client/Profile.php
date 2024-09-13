<?php

namespace App\Entity\Client;

use App\Util\Traits\UserForceItem;
use App\Util\Interfaces\UserInterface;
use App\Repository\Client\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile implements UserInterface
{
    use UserForceItem;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?MediaObject $avatar = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvatar(): ?MediaObject
    {
        return $this->avatar;
    }

    public function setAvatar(?MediaObject $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

}
