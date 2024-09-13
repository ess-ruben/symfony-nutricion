<?php

namespace App\Entity\Payment;

use App\Repository\Payment\TariffRepository;
use App\Util\Interfaces\ActiveInterface;
use App\Util\Traits\ActiveItem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TariffRepository::class)]
class Tariff implements ActiveInterface
{
    use ActiveItem;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    private ?float $priceMontly = null;

    #[ORM\Column(nullable: true)]
    private ?float $priceAnnual = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $workers = null;

    #[ORM\Column(nullable: true)]
    private ?int $clients = null;

    #[ORM\OneToMany(mappedBy: 'tariff', targetEntity: Subscription::class)]
    private Collection $subscriptions;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?bool $isDefault = null;

    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceMontly(): ?float
    {
        return $this->priceMontly;
    }

    public function setPriceMontly(?float $priceMontly): self
    {
        $this->priceMontly = $priceMontly;

        return $this;
    }

    public function getPriceAnnual(): ?float
    {
        return $this->priceAnnual;
    }

    public function setPriceAnnual(?float $priceAnnual): self
    {
        $this->priceAnnual = $priceAnnual;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getWorkers(): ?int
    {
        return $this->workers;
    }

    public function setWorkers(?int $workers): self
    {
        $this->workers = $workers;

        return $this;
    }

    public function getClients(): ?int
    {
        return $this->clients;
    }

    public function setClients(?int $clients): self
    {
        $this->clients = $clients;

        return $this;
    }

    /**
     * @return Collection<int, Subscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->setTariff($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getTariff() === $this) {
                $subscription->setTariff(null);
            }
        }

        return $this;
    }

    public function isIsDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(?bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }
}
