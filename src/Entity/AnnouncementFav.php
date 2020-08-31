<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AnnouncementFavRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AnnouncementFavRepository::class)
 * @ApiResource(
 *      attributes={
 *      "order"={"favAt": "DESC"}
 *      },
 *      normalizationContext={"groups"={"announcementFav:read"}},
 *      itemOperations={
 *          "delete"={
 *              "path"="/v1/announcement/favs/{id}",
 *              "security" = "is_granted('delete_announcementFav', object)",  
 *          }
 *      }
 * )
 * 
 */
class AnnouncementFav
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"announcementFav:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Announcement::class, inversedBy="favorites")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"announcementFav:read"})
     */
    private $announcement;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="favorites")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"announcementFav:read"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $favAt;


    public function __construct()
    {
        
        $this->favAt = new \DateTime;
    }

    public function __toString()
    {
        return $this->user->__toString() . ' a mis en favori ' . $this->announcement->__toString();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnouncement(): ?Announcement
    {
        return $this->announcement;
    }

    public function setAnnouncement(?Announcement $announcement): self
    {
        $this->announcement = $announcement;

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

    public function getFavAt(): ?\DateTimeInterface
    {
        return $this->favAt;
    }

    public function setFavAt(\DateTimeInterface $favAt): self
    {
        $this->favAt = $favAt;

        return $this;
    }
 
}
