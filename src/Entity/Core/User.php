<?php

namespace App\Entity\Core;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Util\Enum\UserStatus;
use App\Util\Interfaces\TimeInterface;
use App\Util\Traits\TimeItem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\Core\UserRepository;
use App\EntityListener\Core\UserListener;
use App\Entity\Calendar\Meeting;
use Doctrine\ORM\Mapping as ORM;

#[ORM\EntityListeners([UserListener::class])]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimeItem;
    
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_ADMIN_WORKER = 'ROLE_ADMIN_WORKER';
    const ROLE_BUSINESS = 'ROLE_BUSINESS';
    const ROLE_BUSINESS_WORKER = 'ROLE_BUSINESS_WORKER';
    const ROLE_BUSINESS_ADMINISTRATION = 'ROLE_BUSINESS_ADMINISTRATION';
    const ROLE_USER = 'ROLE_USER';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $surnames = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    private ?string $plainPassword = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ApiProperty(['security'=>'is_granted("ROLE_ADMIN")'])]
    #[ORM\Column]
    private array $roles = [];

    private string $role = "";

    #[ORM\Column]
    private ?bool $active = true;

    #[ORM\ManyToOne(inversedBy: 'community')]
    private ?Business $business = null;

    #[ORM\OneToOne(mappedBy: "bossUser", targetEntity: Business::class)]
    private ?Business $yourBusiness = null;

    #[ORM\Column(nullable: true)]
    private ?int $tall = null;

    #[ORM\Column(nullable: true, options: ['default' => 1])]
    private ?int $gender = null;

    public function __construct()
    {
        $this->roles=['ROLE_USER'];
        $this->gender = UserStatus::GENDER_MALE;
    }
    
    public function __toString(){
        return $this->name." ".$this->surnames;
    }

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->setUpdatedAt(new \DateTime());
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRole(): string
    {
        foreach ($this->roles as $rol) {
            return $rol;
        }
        
        return User::ROLE_USER;
    }

    public function setRole(string $roles): self
    {
        $this->roles = [$roles];

        return $this;
    }

    public function hasRole($role)
    {
        return array_search($role, $this->roles) !== FALSE;
    }

    public function isBossAdmin()
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    public function isWorkerAdmin()
    {
        return $this->hasRole(self::ROLE_ADMIN_WORKER);
    }

    public function isUserAdmin()
    {
        return $this->isBossAdmin() || $this->isWorkerAdmin();
    }

    public function isBossBusiness()
    {
        return $this->hasRole(self::ROLE_BUSINESS);
    }

    public function isWorkerBusiness()
    {
        return $this->hasRole(self::ROLE_BUSINESS_WORKER);
    }

    public function isAdministrationBusiness()
    {
        return $this->hasRole(self::ROLE_BUSINESS_ADMINISTRATION);
    }

    public function isUserBusiness()
    {
        return $this->isBossBusiness() || $this->isWorkerBusiness() || $this->isAdministrationBusiness();
    }

    public function isClient()
    {
        return count($this->getRoles()) == 1 && $this->hasRole(self::ROLE_USER);
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function getUsername(): ?string
    {
        return $this->getEmail();
    }

    public function getSurnames(): ?string
    {
        return $this->surnames;
    }

    public function setSurnames(string $surnames): self
    {
        $this->surnames = $surnames;

        return $this;
    }

    public function getBusiness(): ?Business
    {
        return $this->business;
    }

    public function setBusiness(?Business $business): self
    {
        $this->business = $business;

        return $this;
    }

    public function getYourBusiness(): ?Business
    {
        return $this->yourBusiness;
    }

    public function setYourBusiness(?Business $yourBusiness): self
    {
        $this->yourBusiness = $yourBusiness;

        return $this;
    }

    public function getTall(): ?int
    {
        return $this->tall;
    }

    public function setTall(?int $tall): self
    {
        $this->tall = $tall;

        return $this;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(?int $sex): self
    {
        $this->gender = $sex;

        return $this;
    }
}
