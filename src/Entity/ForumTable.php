<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumTableRepository")
 */
class ForumTable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=75)
     * @Assert\Length(min=3, max=70, minMessage="Nom de forum trop court", maxMessage="Nom de forum trop long")
     */
    private $ForumName;

    /**
     * @ORM\Column(type="text", length=128)
     */
    private $ForumDescription;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="forums")
     * @ORM\JoinColumn(nullable=true)
     */
    private $ForumAuthor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CatTable", inversedBy="forums")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     *  @ORM\OneToMany(targetEntity="App\Entity\ThreadTable", mappedBy="forum", cascade={"remove"})
     *  @ORM\OrderBy({"id" = "DESC"})
     */
    private $threads;

    public function __construct()
    {
        $this->threads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getForumName(): ?string
    {
        return $this->ForumName;
    }

    public function setForumName(string $ForumName): self
    {
        $this->ForumName = $ForumName;

        return $this;
    }

    public function getForumDescription(): ?string
    {
        return $this->ForumDescription;
    }

    public function setForumDescription(string $ForumDescription): self
    {
        $this->ForumDescription = $ForumDescription;

        return $this;
    }

    public function getForumAuthor(): ?User
    {
        return $this->ForumAuthor;
    }

    public function setForumAuthor(?User $ForumAuthor): self
    {
        $this->ForumAuthor = $ForumAuthor;

        return $this;
    }

    public function getCategory(): ?CatTable
    {
        return $this->category;
    }

    public function setCategory(?CatTable $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     *  @return Collection|ThreadTable[]
     */
    public function getThreads(): Collection
    {
        return $this->threads;
    }

    public function addThreads(ThreadTable $ThreadTable): self
    {
        if (!$this->threads->contains($ThreadTable)) {
            $this->threads[] = $ThreadTable;
            $ThreadTable->setThreadTable($this);
        }

        return $this;
    }

    public function removeThreads(ThreadTable $ThreadTable): self
    {
        if ($this->threads->contains($ThreadTable)) {
            $this->theads->removeElement($ThreadTable);
            if ($ThreadTable->getThreadTable() === $this) {
                $ThreadTable->setThreadTable(null);
            }
        }
    }
}
