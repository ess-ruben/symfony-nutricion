<?php

namespace App\Entity\Notification;

use App\EntityListener\Notification\MessageToUserListener;
use App\Repository\Notification\MessageToUserRepository;
use App\Util\Interfaces\ActiveInterface;
use App\Util\Interfaces\TimeInterface;
use App\Util\Interfaces\UserInterface;
use App\Util\Traits\ActiveItem;
use App\Util\Traits\TimeItem;
use App\Util\Traits\UserForceItem;
use Doctrine\ORM\Mapping as ORM;

#[ORM\EntityListeners([MessageToUserListener::class])]
#[ORM\Entity(repositoryClass: MessageToUserRepository::class)]
class MessageToUser implements TimeInterface,UserInterface, ActiveInterface
{
    use TimeItem;
    use UserForceItem;
    use ActiveItem;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Message $message = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?bool $isReaded = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function isIsReaded(): ?bool
    {
        return $this->isReaded;
    }

    public function setIsReaded(?bool $isReaded): self
    {
        $this->isReaded = $isReaded;

        return $this;
    }

    public function getForceUser():bool
    {
        return false;
    }
}
