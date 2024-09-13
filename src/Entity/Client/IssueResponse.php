<?php

namespace App\Entity\Client;

use App\EntityListener\Client\IssueResponseListener;
use App\Repository\Client\IssueResponseRepository;
use App\Util\Interfaces\NotifyInterface;
use App\Util\Interfaces\TimeInterface;
use App\Util\Interfaces\UserInterface;
use App\Util\Traits\NotifyIssueItem;
use App\Util\Traits\TimeItem;
use App\Util\Traits\UserForceItem;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\EntityListeners([IssueResponseListener::class])]
#[ORM\Entity(repositoryClass: IssueResponseRepository::class)]
class IssueResponse implements UserInterface,TimeInterface,NotifyInterface
{
    use UserForceItem;
    use TimeItem;
    use NotifyIssueItem;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $response = null;

    #[ORM\ManyToOne(inversedBy: 'issueResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Issue $issue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getIssue(): ?Issue
    {
        return $this->issue;
    }

    public function setIssue(?Issue $issue): self
    {
        $this->issue = $issue;

        return $this;
    }
}
