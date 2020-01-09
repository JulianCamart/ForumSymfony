<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReportTableRepository")
 */
class ReportTable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=200)
     * @Assert\Length(min=0, max=200, minMessage="", maxMessage="Votre signalement ne peux contenir plus de 200 caractères")
     * @Assert\NotBlank(message="Veuillez indiquez la raison de ce signalement")
     */
    private $ReportText;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reports")
     * @ORM\JoinColumn(nullable=true)
     */
    private $ReportAuthor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PostTable", inversedBy="reports")
     * @ORM\JoinColumn(nullable=true)
     */
    private $ReportPost;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ThreadTable", inversedBy="reports")
     * @ORM\JoinColumn(nullable=true)
     */
    private $ReportThread;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reports")
     * @ORM\JoinColumn(nullable=true)
     */
    private $ReportUser;

    /**
     * @ORM\Column(name="validation", type="boolean")
     */
    protected $validation = false;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReportText(): ?string
    {
        return $this->ReportText;
    }

    public function setReportText(string $ReportText): self
    {
        $this->ReportText = $ReportText;

        return $this;
    }

    public function getReportAuthor(): ?User
    {
        return $this->ReportAuthor;
    }

    public function setReportAuthor(?User $ReportAuthor): self
    {
        $this->ReportAuthor = $ReportAuthor;

        return $this;
    }

    public function getReportPost(): ?PostTable
    {
        return $this->ReportPost;
    }

    public function setReportPost(?PostTable $ReportPost): self
    {
        $this->ReportPost = $ReportPost;

        return $this;
    }

    public function getReportThread(): ?ThreadTable
    {
        return $this->ReportThread;
    }

    public function setReportThread(?ThreadTable $ReportThread): self
    {
        $this->ReportThread = $ReportThread;

        return $this;
    }

    public function getReportUser(): ?User
    {
        return $this->ReportUser;
    }

    public function setReportUser(?User $ReportUser): self
    {
        $this->ReportUser = $ReportUser;

        return $this;
    }

    public function getValidation(): ?bool
    {
        return $this->validation;
    }

    public function setValidation(bool $validation): self
    {
        $this->validation = $validation;

        return $this;
    }

}