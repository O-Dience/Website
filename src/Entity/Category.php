<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ApiResource(
 *      collectionOperations={"get"},
 *      itemOperations={
 *      "get"={
 *      "path"="/v1/category/{id}",
 *      }
 *    }     
 * )
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"announcementFav:read"})
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picto;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity=Announcement::class, mappedBy="categories")
     */
    private $announcements;

    /**
     * @ORM\OneToMany(targetEntity=UserCategory::class, mappedBy="category", orphanRemoval=true, cascade={"persist"})
     */
    private $user;

   

    public function __construct()
    {
        $this->announcements = new ArrayCollection();
        $this->created_at = new \DateTime;
        $this->user = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->label;
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getPicto()
    {
        return $this->picto;
    }

    public function setPicto($picto)
    {
        $this->picto = $picto;

        return $this;
    }

    public function getPictoWithPath()
    {
        //Set path for easyadmin
        return 'assets/images/category_picto/'.$this->picto;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Announcement[]
     */
    public function getAnnouncements(): Collection
    {
        return $this->announcements;
    }

    public function addAnnouncement(Announcement $announcement): self
    {
        if (!$this->announcements->contains($announcement)) {
            $this->announcements[] = $announcement;
            $announcement->addCategory($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): self
    {
        if ($this->announcements->contains($announcement)) {
            $this->announcements->removeElement($announcement);
            $announcement->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection|UserCategory[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(UserCategory $userCategory): self
    {
        if (!$this->user->contains($userCategory)) {
            $this->user[] = $userCategory;
            $userCategory->setCategory($this);
        }

        return $this;
    }

    public function removeUser(UserCategory $userCategory): self
    {
        if ($this->user->contains($userCategory)) {
            $this->user->removeElement($userCategory);
            // set the owning side to null (unless already changed)
            if ($userCategory->getCategory() === $this) {
                $userCategory->setCategory(null);
            }
        }

        return $this;
    }

   
  
}
