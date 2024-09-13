<?php

namespace App\Entity\Client;

use App\EntityListener\Client\ResetPasswordListener;
use App\Repository\Client\ResetPasswordRepository;
use App\Util\Interfaces\ActiveInterface;
use App\Util\Interfaces\BusinessInterface;
use App\Util\Interfaces\TimeInterface;
use App\Util\Interfaces\UserInterface;
use App\Util\Traits\ActiveItem;
use App\Util\Traits\BusinessItem;
use App\Util\Traits\TimeItem;
use App\Util\Traits\UserForceItem;
use Doctrine\ORM\Mapping as ORM;

#[ORM\EntityListeners([ResetPasswordListener::class])]
#[ORM\Entity(repositoryClass: ResetPasswordRepository::class)]
class ResetPassword implements TimeInterface, ActiveInterface
{
    use TimeItem;
    use ActiveItem;
    use UserForceItem;
    use BusinessItem;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $expiredAt = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpiredAt(): ?\DateTimeInterface
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(\DateTimeInterface $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        $dateString = (new \DateTime('now'))->format('Ydmhis');
        $this->setToken(
            md5($dateString)
        );

        return $this;
    }
}
