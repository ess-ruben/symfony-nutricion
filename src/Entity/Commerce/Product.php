<?php

namespace App\Entity\Commerce;

use ApiPlatform\Metadata\ApiFilter;
use App\Repository\Commerce\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $kcal = null;

    #[ORM\Column(nullable: true)]
    private ?float $grams = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ListProduct $listProduct = null;

    #[ORM\Column(nullable: true)]
    private ?float $proteins = null;

    #[ORM\Column(nullable: true)]
    private ?float $hydrates = null;

    #[ORM\Column(nullable: true)]
    private ?float $fats = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getKcal(): ?float
    {
        return $this->kcal;
    }

    public function setKcal(float $kcal): self
    {
        $this->kcal = $kcal;

        return $this;
    }

    public function getGrams(): ?float
    {
        return $this->grams;
    }

    public function setGrams(?float $grams): self
    {
        $this->grams = $grams;

        return $this;
    }

    public function getListProduct(): ?ListProduct
    {
        return $this->listProduct;
    }

    public function setListProduct(?ListProduct $listProduct): self
    {
        $this->listProduct = $listProduct;

        return $this;
    }

    public function getProteins(): ?float
    {
        return $this->proteins;
    }

    public function setProteins(?float $proteins): self
    {
        $this->proteins = $proteins;

        return $this;
    }

    public function getHydrates(): ?float
    {
        return $this->hydrates;
    }

    public function setHydrates(?float $hydrates): self
    {
        $this->hydrates = $hydrates;

        return $this;
    }

    public function getFats(): ?float
    {
        return $this->fats;
    }

    public function setFats(?float $fats): self
    {
        $this->fats = $fats;

        return $this;
    }
}
