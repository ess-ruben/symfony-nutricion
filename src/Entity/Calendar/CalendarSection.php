<?php

namespace App\Entity\Calendar;

use App\Repository\Calendar\CalendarSectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalendarSectionRepository::class)]
class CalendarSection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $sortSection = null;

    #[ORM\ManyToOne(inversedBy: 'calendarSections')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CalendarUser $calendar = null;

    #[ORM\OneToMany(mappedBy: 'calendarSection', targetEntity: CalendarItem::class, orphanRemoval: true)]
    private Collection $calendarItems;

    public function __construct()
    {
        $this->calendarItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSortSection(): ?int
    {
        return $this->sortSection;
    }

    public function setSortSection(int $sortSection): self
    {
        $this->sortSection = $sortSection;

        return $this;
    }

    public function getCalendar(): ?CalendarUser
    {
        return $this->calendar;
    }

    public function setCalendar(?CalendarUser $calendar): self
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
            $calendarItem->setCalendarSection($this);
        }

        return $this;
    }

    public function removeCalendarItem(CalendarItem $calendarItem): self
    {
        if ($this->calendarItems->removeElement($calendarItem)) {
            // set the owning side to null (unless already changed)
            if ($calendarItem->getCalendarSection() === $this) {
                $calendarItem->setCalendarSection(null);
            }
        }

        return $this;
    }
}
