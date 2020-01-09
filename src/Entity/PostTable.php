<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PostTableRepository")
 */
class PostTable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $PostText;

    /**
     * @ORM\Column(type="datetime")
     */
    private $PostTime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ThreadTable", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $thread;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=true)
     */
    private $PostAuthor;

    /**
     * @ORM\Column(name="report", type="integer")
     */
    protected $report = 0;

    /**
     *  @ORM\OneToMany(targetEntity="App\Entity\ReportTable", mappedBy="reportPost", cascade={"remove"})
     */
    private $reports;

    public function __construct()
    {
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostText(): ?string
    {
        return $this->PostText;
    }

    public function setPostText(string $PostText): self
    {
        $this->PostText = $PostText;

        return $this;
    }

    public function getPostTime(): ?\DateTimeInterface
    {
        return $this->PostTime;
    }

    public function setPostTime(\DateTimeInterface $PostTime): self
    {
        $this->PostTime = $PostTime;

        return $this;
    }

    public function getThread(): ?ThreadTable
    {
        return $this->thread;
    }

    public function setThread(?ThreadTable $thread): self
    {
        $this->thread = $thread;

        return $this;
    }

    public function getPostAuthor(): ?User
    {
        return $this->PostAuthor;
    }

    public function setPostAuthor(?User $PostAuthor): self
    {
        $this->PostAuthor = $PostAuthor;

        return $this;
    }

    //////////////////////////////////////LES REPORTS///////////////////////////////////////////////

    public function getReport(): ?int
    {
        return $this->report;
    }

    public function setReport(int $report): self
    {
        $this->report = $report;

        return $this;
    }

    /**
     *  @return Collection|ReportTable[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(ReportTable $reportTable): self
    {
        if (!$this->reports->contains($reportTable)) {
            $this->reports[] = $reportTable;
            $reportTable->setReportTable($this);
        }

        return $this;
    }

    public function removeReports(ReportTable $reportTable): self
    {
        if ($this->reports->contains($reportTable)) {
            $this->reports->removeElement($reportTable);
            if ($reportTable->getReportTable() === $this) {
                $reportTable->setReportTable(null);
            }
        }
    }

}
