<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"announcementFav:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $username;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;


    /**
     * @ORM\OneToMany(targetEntity=UserSocial::class, mappedBy="user", orphanRemoval=true, cascade={"persist"})
     * 
     */
    private $userSocials;


    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="users")
     * 
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Announcement::class, mappedBy="user", orphanRemoval=true)
     */
    private $announcements;

     /**
     * @ORM\OneToMany(targetEntity=AnnouncementFav::class, mappedBy="user", orphanRemoval=true)
     */
    private $favorites;

    /**
     * @ORM\OneToMany(targetEntity=UserFav::class, mappedBy="userLiked", orphanRemoval=true)
     */
    private $userFavs;

      /**
     * @ORM\OneToMany(targetEntity=UserFav::class, mappedBy="userLike", orphanRemoval=true)
     */
    private $userFavorites;


    /**
     * @ORM\OneToMany(targetEntity=AnnouncementReport::class, mappedBy="reporter", orphanRemoval=true)
     */
    private $reportedAnnouncements;

    /**
     * @ORM\OneToMany(targetEntity=UserReport::class, mappedBy="reporter", orphanRemoval=true)
     */
    private $reportedUsers;

    /**
     * @ORM\OneToMany(targetEntity=UserReport::class, mappedBy="reportee", orphanRemoval=true)
     */
    private $reportedBy;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
  

    public function __construct(array $data = [])
    {

        $this->userSocials = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->announcements = new ArrayCollection();
        $this->created_at = new \DateTime;
        $this->status = 1; // 1 = active
        $this->userFavs = new ArrayCollection();
        $this->reportedAnnouncements = new ArrayCollection();
        $this->reportedUsers = new ArrayCollection();
        $this->reportedBy = new ArrayCollection();
       }

    public function __toString()
    {
        return $this->username;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }


    public function getFrenchRole()
    {
        //Set path for easyadmin
        if (in_array('ROLE_BRAND', $this->roles)){
            return 'Marque';
        }
        if (in_array('ROLE_INFLUENCER', $this->roles)){
            return 'Influenceur';
        }
        if (in_array('ROLE_MODERATOR', $this->roles)){
            return 'Modérateur';
        }
        if (in_array('ROLE_MODERATOR', $this->roles)){
            return 'Modérateur';
        }
        if (in_array('ROLE_ADMIN', $this->roles)){
            return 'Administrateur';
        }
        
    }
    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

        /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }
    
    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPictureWithPath()
    {
        //Set path for easyadmin
        return 'assets/images/avatar_user/'.$this->picture;
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
     * @return Collection|UserSocial[]
     */
    public function getUserSocials(): Collection
    {
        return $this->userSocials;
    }

    public function addUserSocial(UserSocial $userSocial): self
    {
        if (!$this->userSocials->contains($userSocial)) {
            $this->userSocials[] = $userSocial;
            $userSocial->setUser($this);
        }

        return $this;
    }

    public function removeUserSocial(UserSocial $userSocial): self
    {
        if ($this->userSocials->contains($userSocial)) {
            $this->userSocials->removeElement($userSocial);
            // set the owning side to null (unless already changed)
            if ($userSocial->getUser() === $this) {
                $userSocial->setUser(null);
            }
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
            $announcement->setUser($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): self
    {
        if ($this->announcements->contains($announcement)) {
            $this->announcements->removeElement($announcement);
            // set the owning side to null (unless already changed)
            if ($announcement->getUser() === $this) {
                $announcement->setUser(null);
            }
        }

        return $this;
    }

    

    /**
     * Get the value of favorites
     */ 
    public function getFavorites()
    {
        return $this->favorites;
    }

    /**
     * Set the value of favorites
     *
     * @return  self
     */ 
    public function setFavorites($favorites)
    {
        $this->favorites = $favorites;

        return $this;
    }

    /**
     * @return Collection|UserFav[]
     */
    public function getUserFavs(): Collection
    {
        return $this->userFavs;
    }

    public function addUserFav(UserFav $userFav): self
    {
        if (!$this->userFavs->contains($userFav)) {
            $this->userFavs[] = $userFav;
            $userFav->setUserLiked($this);
        }

        return $this;
    }

    public function removeUserFav(UserFav $userFav): self
    {
        if ($this->userFavs->contains($userFav)) {
            $this->userFavs->removeElement($userFav);
            // set the owning side to null (unless already changed)
            if ($userFav->getUserLiked() === $this) {
                $userFav->setUserLiked(null);
            }
        }

        return $this;
    }



    public function isFavByUser(User $user): bool {
        foreach($this->userFavs as $fav){
            if($fav->getUserLike() === $user){
                return true;
            }
           
        }
        return false;
    }
    
    
   

    /**
     * Get the value of userFavorites
     */ 
    public function getUserFavorites()
    {
        return $this->userFavorites;
    }

    /**
     * Set the value of userFavorites
     *
     * @return  self
     */ 
    public function setUserFavorites($userFavorites)
    {
        $this->userFavorites = $userFavorites;

        return $this;
    }

    /**
     * @return Collection|AnnouncementReport[]
     */
    public function getReportedAnnouncements(): Collection
    {
        return $this->reportedAnnouncements;
    }

    public function addReportedAnnouncement(AnnouncementReport $reportedAnnouncement): self
    {
        if (!$this->reportedAnnouncements->contains($reportedAnnouncement)) {
            $this->reportedAnnouncements[] = $reportedAnnouncement;
            $reportedAnnouncement->setReporter($this);
        }

        return $this;
    }

    public function removeReportedAnnouncement(AnnouncementReport $reportedAnnouncement): self
    {
        if ($this->reportedAnnouncements->contains($reportedAnnouncement)) {
            $this->reportedAnnouncements->removeElement($reportedAnnouncement);
            // set the owning side to null (unless already changed)
            if ($reportedAnnouncement->getReporter() === $this) {
                $reportedAnnouncement->setReporter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserReport[]
     */
    public function getReportedUsers(): Collection
    {
        return $this->reportedUsers;
    }

    public function addReportedUser(UserReport $reportedUser): self
    {
        if (!$this->reportedUsers->contains($reportedUser)) {
            $this->reportedUsers[] = $reportedUser;
            $reportedUser->setReporter($this);
        }

        return $this;
    }

    public function removeReportedUser(UserReport $reportedUser): self
    {
        if ($this->reportedUsers->contains($reportedUser)) {
            $this->reportedUsers->removeElement($reportedUser);
            // set the owning side to null (unless already changed)
            if ($reportedUser->getReporter() === $this) {
                $reportedUser->setReporter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserReport[]
     */
    public function getReportedBy(): Collection
    {
        return $this->reportedBy;
    }

    public function addReportedBy(UserReport $reportedBy): self
    {
        if (!$this->reportedBy->contains($reportedBy)) {
            $this->reportedBy[] = $reportedBy;
            $reportedBy->setReportee($this);
        }

        return $this;
    }

    public function removeReportedBy(UserReport $reportedBy): self
    {
        if ($this->reportedBy->contains($reportedBy)) {
            $this->reportedBy->removeElement($reportedBy);
            // set the owning side to null (unless already changed)
            if ($reportedBy->getReportee() === $this) {
                $reportedBy->setReportee(null);
            }
        }

        return $this;
    }

    //function to check if another user was reported by this user
    public function isReportedByUser(User $user) : bool
    {
        foreach($this->reportedBy as $report){
            if ($report->getReporter() === $user) return true;
        }

        return false;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

}
