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

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_confirmed;

    public function __construct()
    {
        $this->is_confirmed = false;
    }

    public function __toString()
    {
        return $this->reporter->__toString() . ' signale ' . $this->reportee->__toString();
    }

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

    public function getIsConfirmed(): ?bool
    {
        return $this->is_confirmed;
    }

    public function setIsConfirmed(bool $is_confirmed): self
    {
        $this->is_confirmed = $is_confirmed;

        return $this;
    }
}