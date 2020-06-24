<?php

namespace App\Entity;

use App\Repository\UserFavRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserFavRepository::class)
 */
class UserFav
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userFavs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userLiked;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userFavorites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userLike;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserLiked(): ?User
    {
        return $this->userLiked;
    }

    public function setUserLiked(?User $userLiked): self
    {
        $this->userLiked = $userLiked;

        return $this;
    }

    public function getUserLike(): ?User
    {
        return $this->userLike;
    }

    public function setUserLike(?User $userLike): self
    {
        $this->userLike = $userLike;

        return $this;
    }
}
