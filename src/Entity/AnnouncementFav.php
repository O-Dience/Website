<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiResource;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\AnnouncementFavRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnouncementFavRepository::class)
 * @ApiResource(
 *      attributes={"order"={"id":"DESC"}},
 *      normalizationContext={"groups"={"read:announcementFav"}},
 *      collectionOperations={"get"},
 *      itemOperations={"get"}
 * )
 */
class AnnouncementFav
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read:announcementFav"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Announcement::class, inversedBy="favorites")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read:announcementFav"})
     */
    private $announcement;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="favorites")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read:announcementFav"})
     */
    private $user;



  

    

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

 
}
