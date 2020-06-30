<?php

namespace App\Entity;


use App\Repository\AnnouncementRepository;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=AnnouncementRepository::class)

 */
class Announcement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"announcementFav:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"announcementFav:read"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"announcementFav:read"})
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"announcementFav:read"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"announcementFav:read"})
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity=SocialNetwork::class, inversedBy="announcements")
     */
    private $socialNetworks;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="announcements")
     * @Groups({"announcementFav:read"})
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="announcements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=AnnouncementFav::class, mappedBy="announcement", orphanRemoval=true)
     */
    private $favorites;

    /**
     * @ORM\OneToMany(targetEntity=AnnouncementReport::class, mappedBy="announcement", orphanRemoval=true)
     */
    private $reports;


    

    public function __construct()
    {
        $this->socialNetworks = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->created_at = new \DateTime;
        $this->status = true; // true = active

        $this->favorites = new ArrayCollection();
        $this->reports = new ArrayCollection();       }

    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getImageWithPath()
    {
        //Set path for easyadmin
        return 'assets/images/image_announcement/'.$this->image;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
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
     * @return Collection|SocialNetwork[]
     */
    public function getSocialNetworks(): Collection
    {
        return $this->socialNetworks;
    }

    public function addSocialNetwork(SocialNetwork $socialNetwork): self
    {
        if (!$this->socialNetworks->contains($socialNetwork)) {
            $this->socialNetworks[] = $socialNetwork;
        }

        return $this;
    }

    public function removeSocialNetwork(SocialNetwork $socialNetwork): self
    {
        if ($this->socialNetworks->contains($socialNetwork)) {
            $this->socialNetworks->removeElement($socialNetwork);
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

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

    /**
     * @return Collection|AnnouncementFav[]
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(AnnouncementFav $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->setAnnouncement($this);
        }

        return $this;
    }

    public function removeFavorite(AnnouncementFav $favorite): self
    {
        if ($this->favorites->contains($favorite)) {
            $this->favorites->removeElement($favorite);
            // set the owning side to null (unless already changed)
            if ($favorite->getAnnouncement() === $this) {
                $favorite->setAnnouncement(null);
            }
        }

        return $this;
    }

    //function for check if an announcement is in the user's favorites
    public function isFavByUser(User $user) : bool
    {
        foreach($this->favorites as $favorite){
            if ($favorite->getUser() === $user) return true;
        }

        return false;
    }

    /**
     * @return Collection|AnnouncementReport[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(AnnouncementReport $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setAnnouncement($this);
        }

        return $this;
    }

    public function removeReport(AnnouncementReport $report): self
    {
        if ($this->reports->contains($report)) {
            $this->reports->removeElement($report);
            // set the owning side to null (unless already changed)
            if ($report->getAnnouncement() === $this) {
                $report->setAnnouncement(null);
            }
        }

        return $this;
    }

    //function to check if an announcement was reported by this user
    public function isReportedByUser(User $user) : bool
    {
        foreach($this->reports as $report){
            if ($report->getReporter() === $user) return true;
        }

        return false;
    }

   
}
