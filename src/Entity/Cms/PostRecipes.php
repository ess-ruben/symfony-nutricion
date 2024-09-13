<?php

namespace App\Entity\Cms;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use App\Repository\Cms\PostRecipesRepository;
use App\Util\Interfaces\MediaInterface;
use App\Util\Interfaces\NotifyInterface;
use App\Util\Traits\MediaFileItem;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[Vich\Uploadable]
#[ApiFilter(BooleanFilter::class, properties: ['category.isActive'])]
#[ORM\Entity(repositoryClass: PostRecipesRepository::class)]
class PostRecipes extends Post implements MediaInterface, NotifyInterface
{
    use MediaFileItem;

    #[ORM\Column(nullable: true)]
    private ?float $kcal = null;

    #[ORM\Column(nullable: true)]
    private ?float $minutes = null;

    public function getKcal(): ?float
    {
        return $this->kcal;
    }

    public function setKcal(?float $kcal): self
    {
        $this->kcal = $kcal;

        return $this;
    }

    public function getMinutes(): ?float
    {
        return $this->minutes;
    }

    public function setMinutes(?float $minutes): self
    {
        $this->minutes = $minutes;

        return $this;
    }

    public function getDiscr(){
        return Post::POST_RECIPES;
    }
}
