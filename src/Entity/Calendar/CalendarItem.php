<?php

namespace App\Entity\Calendar;

use App\Repository\Calendar\CalendarItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CalendarItemRepository::class)]
#[UniqueEntity(['calendarDay','calendarSection'])]
class CalendarItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'calendarItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CalendarDay $calendarDay = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'calendarItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CalendarSection $calendarSection = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCalendarDay(): ?CalendarDay
    {
        return $this->calendarDay;
    }

    public function setCalendarDay(?CalendarDay $calendarDay): self
    {
        $this->calendarDay = $calendarDay;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCalendarSection(): ?CalendarSection
    {
        return $this->calendarSection;
    }

    public function setCalendarSection(?CalendarSection $calendarSection): self
    {
        $this->calendarSection = $calendarSection;

        return $this;
    }
}
