<?php

namespace App\Entity\Core;

use App\Repository\Core\BusinessMediaRepository;
use App\Util\Enum\MediaSection;
use App\Util\Interfaces\BusinessInterface;
use App\Util\Interfaces\MediaInterface;
use App\Util\Traits\BusinessItem;
use App\Util\Traits\MediaFileItem;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BusinessMediaRepository::class)]
class BusinessMedia implements BusinessInterface,MediaInterface
{
    use BusinessItem;
    use MediaFileItem;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?int $sortOrder = 0;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?int $section = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $urlSponsor = null;

    public function __construct() {
        $this->section = MediaSection::MEDIA_GALLERY;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(?int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function getSection(): ?int
    {
        return $this->section;
    }

    public function setSection(?int $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getUrlSponsor(): ?string
    {
        return $this->urlSponsor;
    }

    public function setUrlSponsor(?string $urlSponsor): self
    {
        $this->urlSponsor = $urlSponsor;

        return $this;
    }
}
