<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ThreadTableRepository")
 */
class ThreadTable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(min=3, max=100, minMessage="Nom de topic trop court", maxMessage="Nom de topic trop long")
     */
    private $ThreadName;

    /**
     * @ORM\Column(type="text")
     */
    private $ThreadText;

    /**
     * @ORM\Column(type="datetime")
     */
    private $ThreadTime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumTable", inversedBy="threads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $forum;

    /**
     *  @ORM\OneToMany(targetEntity="App\Entity\PostTable", mappedBy="thread", cascade={"remove"})
     */
    private $posts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="threads")
     * @ORM\JoinColumn(nullable=true)
     */
    private $ThreadAuthor;

    /**
     * @ORM\Column(name="report", type="integer")
     */
    protected $report = 0;

    /**
     *  @ORM\OneToMany(targetEntity="App\Entity\ReportTable", mappedBy="reportThread", cascade={"remove"})
     */
    private $reports;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getThreadName(): ?string
    {
        return $this->ThreadName;
    }

    public function setThreadName(string $ThreadName): self
    {
        $this->ThreadName = $ThreadName;

        return $this;
    }

    public function getThreadText(): ?string
    {
        return $this->ThreadText;
    }

    public function setThreadText(string $ThreadText): self
    {
        $this->ThreadText = $ThreadText;

        return $this;
    }

    public function getThreadTime(): ?\DateTimeInterface
    {
        return $this->ThreadTime;
    }

    public function setThreadTime(\DateTimeInterface $ThreadTime): self
    {
        $this->ThreadTime = $ThreadTime;

        return $this;
    }

    public function getForum(): ?ForumTable
    {
        return $this->forum;
    }

    public function setForum(?ForumTable $forum): self
    {
        $this->forum = $forum;

        return $this;
    }

    public function getThreadAuthor(): ?User
    {
        return $this->ThreadAuthor;
    }

    public function setThreadAuthor(?User $ThreadAuthor): self
    {
        $this->ThreadAuthor = $ThreadAuthor;

        return $this;
    }

    /**
     *  @return Collection|PostTable[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPosts(PostTable $PostTable): self
    {
        if (!$this->posts->contains($PostTable)) {
            $this->posts[] = $PostTable;
            $PostTable->setPostTable($this);
        }

        return $this;
    }

    public function removePosts(PostTable $PostTable): self
    {
        if ($this->posts->contains($PostTable)) {
            $this->posts->removeElement($PostTable);
            if ($PostTable->getPostTable() === $this) {
                $PostTable->setPostTable(null);
            }
        }
    }

    /////////////////////////////////////////////LES REPORTS////////////////////////////////////////

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
