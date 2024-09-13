<?php

namespace App\Entity\Payment;

use App\Util\Enum\PaymentStatus;
use App\Util\Enum\SubscriptionType;
use App\Util\Interfaces\BusinessInterface;
use App\Util\Interfaces\TimeInterface;
use App\Util\Traits\BusinessItem;
use App\Util\Traits\TimeItem;
use App\EntityListener\Payment\PaymentListener;
use App\Repository\Payment\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\EntityListeners([PaymentListener::class])]
#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment implements TimeInterface,BusinessInterface
{

    use BusinessItem;
    use TimeItem;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tariff $tariff = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?int $status = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?int $type = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\OneToOne(mappedBy: 'lastPayment', cascade: ['persist', 'remove'])]
    private ?Subscription $subscription = null;

    public function __construct()
    {
        $this->status = PaymentStatus::PREPARE;
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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

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

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(?Subscription $subscription): self
    {
        // unset the owning side of the relation if necessary
        if ($subscription === null && $this->subscription !== null) {
            $this->subscription->setLastPayment(null);
        }

        // set the owning side of the relation if necessary
        if ($subscription !== null && $subscription->getLastPayment() !== $this) {
            $subscription->setLastPayment($this);
        }

        $this->subscription = $subscription;

        return $this;
    }
}
