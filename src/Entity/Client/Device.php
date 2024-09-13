<?php

namespace App\Entity\Client;

use App\Repository\Client\DeviceRepository;
use App\Util\Interfaces\TimeInterface;
use App\Util\Interfaces\UserInterface;
use App\Util\Traits\TimeItem;
use App\Util\Traits\UserForceItem;
use App\Util\Traits\UserItem;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
class Device implements UserInterface,TimeInterface
{
    use UserForceItem;
    use TimeItem;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length: 255)]
    private ?string $uuid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cordova = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $model = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $platform = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $manufacturer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lang = null;

    public function getId(): ?string
    {
        return $this->uuid;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): ?self
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getCordova(): ?string
    {
        return $this->cordova;
    }

    public function setCordova(?string $cordova): self
    {
        $this->cordova = $cordova;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(?string $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?string $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(?string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }
}
