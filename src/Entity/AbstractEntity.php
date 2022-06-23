<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id;

    #[ORM\Column(type: 'datetime')]
    protected ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    protected ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist, ORM\PreUpdate]
    public function setDateTimeValue(): void
    {
        if (null === $this->createdAt) {
            $this->createdAt = new \DateTime();
        }

        $this->updatedAt = new \DateTime();
    }
}
