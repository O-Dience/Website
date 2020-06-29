<?php

namespace App\Entity;

use App\Repository\AnnouncementReportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnouncementReportRepository::class)
 */
class AnnouncementReport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reportedAnnouncements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reporter;

    /**
     * @ORM\ManyToOne(targetEntity=Announcement::class, inversedBy="reports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $announcement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReporter(): ?User
    {
        return $this->reporter;
    }

    public function setReporter(?User $reporter): self
    {
        $this->reporter = $reporter;

        return $this;
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
}
