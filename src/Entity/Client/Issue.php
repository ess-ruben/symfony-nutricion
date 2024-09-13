<?php

namespace App\Entity\Client;

use App\Repository\Client\IssueRepository;
use App\Util\Interfaces\TimeInterface;
use App\Util\Interfaces\UserInterface;
use App\Util\Traits\TimeItem;
use App\Util\Traits\UserForceItem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IssueRepository::class)]
class Issue implements UserInterface,TimeInterface
{
    use UserForceItem;
    use TimeItem;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $body = null;

    #[ORM\OneToMany(mappedBy: 'issue', targetEntity: IssueResponse::class)]
    private Collection $issueResponses;

    #[ORM\Column(nullable: true, options: ['default' => 1])]
    private ?bool $isOpen = true;

    public function __construct()
    {
        $this->issueResponses = new ArrayCollection();
    }

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

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return Collection<int, IssueResponse>
     */
    public function getIssueResponses(): Collection
    {
        return $this->issueResponses;
    }

    public function getIssueResponsesSort(): Collection
    {
        $sortResponse = $this->getIssueResponses()->toArray();
        usort($sortResponse, function ($one, $two)
        {
            $oneAt = $one->getCreateAt() ?? new \DateTime('now');
            $twoAt = $two->getCreateAt() ?? new \DateTime('now');
            return $oneAt->getTimestamp() > $twoAt->getTimestamp();
        });

        return new ArrayCollection($sortResponse);
    }

    public function addIssueResponse(IssueResponse $issueResponse): self
    {
        if (!$this->issueResponses->contains($issueResponse)) {
            $this->issueResponses->add($issueResponse);
            $issueResponse->setIssue($this);
        }

        return $this;
    }

    public function removeIssueResponse(IssueResponse $issueResponse): self
    {
        if ($this->issueResponses->removeElement($issueResponse)) {
            // set the owning side to null (unless already changed)
            if ($issueResponse->getIssue() === $this) {
                $issueResponse->setIssue(null);
            }
        }

        return $this;
    }

    public function isIsOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function setIsOpen(?bool $isOpen): self
    {
        $this->isOpen = $isOpen;

        return $this;
    }
}
