<?php

namespace App\Util\Traits;

use Doctrine\ORM\Mapping as ORM;

trait TimeItem
{

    #[ORM\Column(type: 'datetime', nullable: true,options: ['default' => 'CURRENT_TIMESTAMP'])]
    public ?\DateTimeInterface $createAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    public ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    public ?\DateTimeInterface $deletedAt = null;

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt; 
    }

    public function setCreateAt(?\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}