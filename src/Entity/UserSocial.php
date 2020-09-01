<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserSocialRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=UserSocialRepository::class)
 * @ApiResource(
 *      routePrefix="/v1",
 *      normalizationContext={"groups"={"userSocial:read"}},
 *      itemOperations={
 *          "delete"={
 *              "path"="/user/social/{id}",
 *              "security" = "is_granted('delete_userSocial', object)",  
 *          },
 *          "get"={
 *              "path"="/user_social/{id}",
 *          }
 *      }     
 * )
 */
class UserSocial
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"userSocial:read"})
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"userSocial:read"})
     */
    private $link;
    
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userSocials")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"userSocial:read"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=SocialNetwork::class, inversedBy="userSocials")
     * @Groups({"userSocial:read"})
     */
    private $social;


    public function __toString(){

        return (string) $this->social->getName();
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
