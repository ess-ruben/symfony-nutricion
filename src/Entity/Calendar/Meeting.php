<?php

namespace App\Entity\Calendar;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use App\Entity\Client\UserMeasure;
use App\Entity\Client\MediaObject;
use App\Repository\Calendar\MeetingRepository;
use App\Util\Enum\MeetingStatus;
use App\Util\Interfaces\BusinessInterface;
use App\Util\Interfaces\CreatorInterface;
use App\Util\Interfaces\NotifyInterface;
use App\Util\Interfaces\UserInterface;
use App\Util\Interfaces\TimeInterface;
use App\Util\Traits\BusinessItem;
use App\Util\Traits\CreatorItem;
use App\Util\Traits\NotifyUserItem;
use App\Util\Traits\UserForceItem;
use App\Util\Traits\TimeItem;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeetingRepository::class)]
class Meeting implements TimeInterface,BusinessInterface,UserInterface,CreatorInterface,NotifyInterface
{
    //si añadimos alguna relación de OneToMany o ManyToMany meterle extra lazy para que no lo cargue de primeras
    use BusinessItem;
    use UserForceItem;
    use CreatorItem;
    use TimeItem;
    use NotifyUserItem;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ApiFilter(OrderFilter::class)]
    #[ORM\Column(type: 'datetime',nullable: true)]
    private ?\DateTimeInterface $dateAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $status = 0;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $cancelReason = null;

    #[ORM\OneToOne(mappedBy: 'meeting', cascade: ['persist'])]
    private ?UserMeasure $userMeasure = null;

    #[ORM\ManyToOne]
    private ?MediaObject $image = null;

    public function __construct() {
        $this->status = MeetingStatus::PENDING;
    }

    public function __toString()
    {
        $email = $this->getUser() ? $this->getUser()->getEmail() : '-';
        $date = $this->getDateAt() ? $this->getDateAt()->format('Y-m-d H:i') : '0000-00-00 00:00';
        return "cita: $email - $date";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAt(): ?\DateTimeInterface
    {
        return $this->dateAt;
    }

    public function setDateAt(?\DateTimeInterface $dateAt): self
    {
        $this->dateAt = $dateAt;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCancelReason(): ?string
    {
        return $this->cancelReason;
    }

    public function setCancelReason(?string $cancelReason): self
    {
        $this->cancelReason = $cancelReason;

        return $this;
    }

    public function getForceUser():bool
    {
        return false;
    }

    public function getUserMeasure(): ?UserMeasure
    {
        return $this->userMeasure;
    }

    public function setUserMeasure(UserMeasure $userMeasure): self
    {
        // set the owning side of the relation if necessary
        if ($userMeasure->getMeeting() !== $this) {
            $userMeasure->setMeeting($this);
        }

        $this->userMeasure = $userMeasure;

        return $this;
    }

    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    public function setImage(?MediaObject $image): self
    {
        $this->image = $image;

        return $this;
    }
}
