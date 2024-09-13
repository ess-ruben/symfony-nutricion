<?php

namespace App\Entity\Core;

use App\Entity\Client\Address;
use App\Entity\Cms\Post;
use App\EntityListener\Core\BusinessListener;
use App\Repository\Core\BusinessRepository;
use App\Util\Interfaces\ActiveInterface;
use App\Util\Traits\ActiveItem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\EntityListeners([BusinessListener::class])]
#[ORM\Entity(repositoryClass: BusinessRepository::class)]
class Business implements ActiveInterface
{
    use ActiveItem;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(cascade: ["persist"])]
    private ?Address $address = null;

    #[ORM\OneToMany(mappedBy: 'business', targetEntity: Post::class)]
    private Collection $cms;

    #[ORM\OneToMany(mappedBy: 'business', targetEntity: User::class)]
    private Collection $community;

    #[ORM\OneToOne(inversedBy: "yourBusiness", targetEntity: User::class,cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $bossUser = null;

    public function __construct()
    {
        $this->cms = new ArrayCollection();
        $this->community = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getCms(): Collection
    {
        return $this->cms;
    }

    public function addCm(Post $cm): self
    {
        if (!$this->cms->contains($cm)) {
            $this->cms->add($cm);
            $cm->setBusiness($this);
        }

        return $this;
    }

    public function removeCm(Post $cm): self
    {
        if ($this->cms->removeElement($cm)) {
            // set the owning side to null (unless already changed)
            if ($cm->getBusiness() === $this) {
                $cm->setBusiness(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCommunity(): Collection
    {
        return $this->community;
    }

    public function addCommunity(User $user): self
    {
        if (!$this->community->contains($user)) {
            $this->community[] = $user;
            $user->setBusiness($this);
        }
    
        return $this;
    }
    
    public function removeCommunity(User $user): self
    {
        if ($this->community->contains($user)) {
            $this->community->removeElement($user);
            // uncomment if you want to update other side of the bidirectional relationship
            // $user->setBusiness(null);
        }
    
        return $this;
    }

    public function getBossUser(): ?User
    {
        return $this->bossUser;
    }

    public function setBossUser(User $bossUser): self
    {
        $this->bossUser = $bossUser;

        return $this;
    }
}
