<?php

namespace App\Entity\Cms;


use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use App\Repository\Cms\PostRepository;
use App\Util\Interfaces\ActiveInterface;
use App\Util\Interfaces\BusinessInterface;
use App\Util\Interfaces\NotifyInterface;
use App\Util\Interfaces\TimeInterface;
use App\Util\Traits\ActiveItem;
use App\Util\Traits\BusinessItem;
use App\Util\Traits\NotifyBusinessItem;
use App\Util\Traits\TimeItem;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discr', type: 'string')]
#[ORM\DiscriminatorMap(['1' => Post::class, '2' => PostEducation::class, '3' => PostRecipes::class])]
#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiFilter(BooleanFilter::class, properties: ['category.isActive'])]
class Post implements ActiveInterface, BusinessInterface, TimeInterface
{
    use ActiveItem;
    use BusinessItem;
    use TimeItem;
    use NotifyBusinessItem;

    const POST_QUESTION = "1";
    const POST_EDUCTATION = "2";
    const POST_RECIPES = "3";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PostCategory $category = null;

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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getCategory(): ?PostCategory
    {
        return $this->category;
    }

    public function setCategory(?PostCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getDiscr(){
        return self::POST_QUESTION;
    }
}
