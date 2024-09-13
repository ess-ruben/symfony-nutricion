<?php

namespace App\Entity\Calendar;

use App\Util\Traits\UserForceItem;
use App\Util\Interfaces\UserInterface;
use App\Repository\Calendar\CalendarUserHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalendarUserHistoryRepository::class)]
class CalendarUserHistory implements UserInterface
{  
    use UserForceItem;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?CalendarDay $calendarUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCalendarUser(): ?CalendarDay
    {
        return $this->calendarUser;
    }

    public function setCalendarUser(CalendarDay $calendarUser): self
    {
        $this->calendarUser = $calendarUser;

        return $this;
    }
}
