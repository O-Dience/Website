<?php

namespace App\Entity;

use App\Repository\UserReportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserReportRepository::class)
 */
class UserReport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reportedUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reporter;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reportedBy")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reportee;

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

    public function getReportee(): ?User
    {
        return $this->reportee;
    }

    public function setReportee(?User $reportee): self
    {
        $this->reportee = $reportee;

        return $this;
    }
}
