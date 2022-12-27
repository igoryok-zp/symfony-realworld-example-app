<?php

declare(strict_types=1);

namespace App\Entity;

use App\Config\ProfileConfig;
use App\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/** @SuppressWarnings(PHPMD.ShortVariable) */
#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[ORM\Table(name: 'profiles')]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(
        length: ProfileConfig::USERNAME_LENGTH,
        unique: true,
    )]
    private ?string $username = null;

    #[ORM\Column(
        length: ProfileConfig::BIO_LENGTH,
        options: ['default' => ''],
    )]
    private string $bio = '';

    #[ORM\Column(
        length: ProfileConfig::IMAGE_LENGTH,
        nullable: true,
    )]
    private ?string $image = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getBio(): string
    {
        return $this->bio;
    }

    public function setBio(string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
