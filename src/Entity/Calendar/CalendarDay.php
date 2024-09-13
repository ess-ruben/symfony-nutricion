<?php

namespace App\Entity\Calendar;

use App\Repository\Calendar\CalendarDayRepository;
use App\Util\Interfaces\TimeInterface;
use App\Util\Traits\TimeItem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CalendarDayRepository::class)]
#[UniqueEntity(['calendar','day'])]
class CalendarDay implements TimeInterface
{
    use TimeItem;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $day = null;

    #[ORM\ManyToOne(inversedBy: 'calendarDays')]
    private CalendarUser $calendar;

    #[ORM\OneToMany(mappedBy: 'calendarDay', targetEntity: CalendarItem::class, orphanRemoval: true)]
    private Collection $calendarItems;

    public function __construct()
    {
        $this->calendarItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getCalendar(): CalendarUser
    {
        return $this->calendar;
    }

    public function setCalendar(CalendarUser $calendar): self
    {
        $this->calendar = $calendar;

        return $this;
    }

    /**
     * @return Collection<int, CalendarItem>
     */
    public function getCalendarItems(): Collection
    {
        return $this->calendarItems;
    }

    public function addCalendarItem(CalendarItem $calendarItem): self
    {
        if (!$this->calendarItems->contains($calendarItem)) {
            $this->calendarItems->add($calendarItem);
            $calendarItem->setCalendarDay($this);
        }

        return $this;
    }

    public function removeCalendarItem(CalendarItem $calendarItem): self
    {
        if ($this->calendarItems->removeElement($calendarItem)) {
            // set the owning side to null (unless already changed)
            if ($calendarItem->getCalendarDay() === $this) {
                $calendarItem->setCalendarDay(null);
            }
        }

        return $this;
    }
}
