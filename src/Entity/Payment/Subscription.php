<?php

namespace App\Entity\Payment;

use App\Util\Enum\SubscriptionType;
use App\Util\Interfaces\ActiveInterface;
use App\Util\Interfaces\BusinessInterface;
use App\Util\Interfaces\TimeInterface;
use App\Util\Traits\ActiveItem;
use App\Util\Traits\BusinessItem;
use App\Util\Traits\TimeItem;
use App\Repository\Payment\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription implements TimeInterface,BusinessInterface,ActiveInterface
{

    use BusinessItem;
    use TimeItem;
    use ActiveItem;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'subscriptions')]
    private ?Tariff $tariff = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?int $type = 0;

    #[ORM\OneToOne(inversedBy: 'subscription', cascade: ['persist', 'remove'])]
    private ?Payment $lastPayment = null;

    public function __construct()
    {
        $this->type = SubscriptionType::MONTHLY;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTariff(): ?Tariff
    {
        return $this->tariff;
    }

    public function setTariff(?Tariff $tariff): self
    {
        $this->tariff = $tariff;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLastPayment(): ?Payment
    {
        return $this->lastPayment;
    }

    public function setLastPayment(?Payment $lastPayment): self
    {
        $this->lastPayment = $lastPayment;

        return $this;
    }
}
