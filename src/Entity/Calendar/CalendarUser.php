<?php

namespace App\Entity\Calendar;

use App\Util\Traits\UserForceItem;
use App\Util\Traits\MediaFileItem;
use App\Util\Traits\TimeItem;
use App\Util\Interfaces\MediaInterface;
use App\Util\Interfaces\UserInterface;
use App\Util\Interfaces\TimeInterface;
use App\Repository\Calendar\CalendarUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: CalendarUserRepository::class)]
class CalendarUser implements TimeInterface,UserInterface,MediaInterface
{
    // 'EXTRA_LAZY' only loads the relationship when you access the function.  
    use UserForceItem;
    use MediaFileItem;
    use TimeItem;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'calendar', targetEntity: CalendarDay::class, fetch: 'EXTRA_LAZY')]
    private Collection $calendarDays;

    #[ORM\OneToMany(mappedBy: 'calendar', targetEntity: CalendarSection::class, orphanRemoval: true, fetch: 'EXTRA_LAZY')]
    private Collection $calendarSections;

    public function __construct()
    {
        $this->calendarDays = new ArrayCollection();
        $this->calendarSections = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getUser() ? "Calendario: ".$this->getUser()->getName() : '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, CalendarDay>
     */
    public function getCalendarDays(): Collection
    {
        return $this->calendarDays;
    }

    public function addCalendarDay(CalendarDay $calendarDay): self
    {
        if (!$this->calendarDays->contains($calendarDay)) {
            $this->calendarDays->add($calendarDay);
            $calendarDay->setCalendar($this);
        }

        return $this;
    }

    public function removeCalendarDay(CalendarDay $calendarDay): self
    {
        if ($this->calendarDays->removeElement($calendarDay)) {
            // set the owning side to null (unless already changed)
            if ($calendarDay->getCalendar() === $this) {
                $calendarDay->setCalendar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CalendarSection>
     */
    public function getCalendarSections(): Collection
    {
        return $this->calendarSections;
    }

    public function addCalendarSection(CalendarSection $calendarSection): self
    {
        if (!$this->calendarSections->contains($calendarSection)) {
            $this->calendarSections->add($calendarSection);
            $calendarSection->setCalendar($this);
        }

        return $this;
    }

    public function removeCalendarSection(CalendarSection $calendarSection): self
    {
        if ($this->calendarSections->removeElement($calendarSection)) {
            // set the owning side to null (unless already changed)
            if ($calendarSection->getCalendar() === $this) {
                $calendarSection->setCalendar(null);
            }
        }

        return $this;
    }
}
