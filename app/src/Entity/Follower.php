<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FollowerRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: FollowerRepository::class)]
#[ORM\Table(name: 'followers')]
class Follower
{
    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Profile $profile = null;

    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Profile $follower = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $createdAt = null;

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getFollower(): ?Profile
    {
        return $this->follower;
    }

    public function setFollower(?Profile $follower): self
    {
        $this->follower = $follower;

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
}
