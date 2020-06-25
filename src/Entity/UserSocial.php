<?php

namespace App\Entity;

use App\Repository\UserSocialRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserSocialRepository::class)
 */
class UserSocial
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $link;
    
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userSocials")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=SocialNetwork::class, inversedBy="userSocials")
     * @ORM\JoinColumn(nullable=false)
     */
    private $social;


















    public function __toString(){

        return $this->social;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSocial(): ?SocialNetwork
    {
        return $this->social;
    }

    public function setSocial(?SocialNetwork $social): self
    {
        $this->social = $social;

        return $this;
    }
}
