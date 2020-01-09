<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CatTableRepository")
 */
class CatTable 
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id; 

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\Length(min=2, max=36, minMessage="Votre nom de catégorie doit faire minimum 2 caractères", maxMessage="Votre nom de catégorie doit faire maximum 36 caractères")
     */
    private $CatName;

    /**
     *  @ORM\OneToMany(targetEntity="App\Entity\ForumTable", mappedBy="category", cascade={"remove"})
     */
    private $forums;

    public function __construct()
    {
        $this->forums = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatName(): ?string
    {
        return $this->CatName;
    }

    public function setCatName(string $CatName): self
    {
        $this->CatName = $CatName;

        return $this;
    }

    /**
     *  @return Collection|ForumTable[]
     */
    public function getForums(): Collection
    {
        return $this->forums;
    }

    public function addForum(ForumTable $ForumTable): self
    {
        if (!$this->forums->contains($ForumTable)) {
            $this->forums[] = $ForumTable;
            $ForumTable->setCatTable($this);
        }

        return $this;
    }

    public function removeForum(ForumTable $ForumTable): self
    {
        if ($this->forums->contains($ForumTable)) {
            $this->forums->removeElement($ForumTable);
            if ($ForumTable->getCatTable() === $this) {
                $ForumTable->setCatTable(null);
            }
        }
    }

}
