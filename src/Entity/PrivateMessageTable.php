<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrivateMessageTableRepository")
 */
class PrivateMessageTable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="privateMessagesAuthor")
     * @ORM\JoinColumn(nullable=true)
     */
    private $MessageAuthor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="privateMessagesReceiver")
     * @ORM\JoinColumn(nullable=true)
     */
    private $MessageReceiver;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=0, max=6000, minMessage="", maxMessage="Message trop long, ")
     */
    private $MessageText;

    /**
     * @ORM\Column(type="datetime")
     */
    private $MessageTime;

    /**
     * @ORM\Column(name="viewed", type="boolean")
     */
    protected $viewed = false;

    /**
     * @ORM\Column(name="archivedByReceiver", type="boolean")
     */
    protected $archivedByReceiver = false;

    /**
     * @ORM\Column(name="archivedByAuthor", type="boolean")
     */
    protected $archivedByAuthor = false;

    /**
     * @ORM\Column(name="deletedByReceiver", type="boolean")
     */
    protected $deletedByReceiver = false;

    /**
     * @ORM\Column(name="deletedByAuthor", type="boolean")
     */
    protected $deletedByAuthor = false;

    public $ReceiverUsername;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageAuthor(): ?User
    {
        return $this->MessageAuthor;
    }

    public function setMessageAuthor(?User $MessageAuthor): self
    {
        $this->MessageAuthor = $MessageAuthor;

        return $this;
    }

    public function getMessageReceiver(): ?User
    {
        return $this->MessageReceiver;
    }

    public function setMessageReceiver(?User $MessageReceiver): self
    {
        $this->MessageReceiver = $MessageReceiver;

        return $this;
    }

    public function getMessageText(): ?string
    {
        return $this->MessageText;
    }

    public function setMessageText(string $MessageText): self
    {
        $this->MessageText = $MessageText;

        return $this;
    }

    public function getMessageTime(): ?\DateTimeInterface
    {
        return $this->MessageTime;
    }

    public function setMessageTime(\DateTimeInterface $MessageTime): self
    {
        $this->MessageTime = $MessageTime;

        return $this;
    }

    public function getViewed(): ?bool
    {
        return $this->viewed;
    }

    public function setViewed(bool $viewed): self
    {
        $this->viewed = $viewed;

        return $this;
    }

    public function getArchivedByReceiver(): ?bool
    {
        return $this->archivedByReceiver;
    }

    public function setArchivedByReceiver(bool $archivedByReceiver): self
    {
        $this->archivedByReceiver = $archivedByReceiver;

        return $this;
    }

    public function getArchivedByAuthor(): ?bool
    {
        return $this->archivedByAuthor;
    }

    public function setArchivedByAuthor(bool $archivedByAuthor): self
    {
        $this->archivedByAuthor = $archivedByAuthor;

        return $this;
    }

    public function getDeletedByReceiver(): ?bool
    {
        return $this->deletedByReceiver;
    }

    public function setDeletedByReceiver(bool $deletedByReceiver): self
    {
        $this->deletedByReceiver = $deletedByReceiver;

        return $this;
    }

    public function getDeletedByAuthor(): ?bool
    {
        return $this->deletedByAuthor;
    }

    public function setDeletedByAuthor(bool $deletedByAuthor): self
    {
        $this->deletedByAuthor = $deletedByAuthor;

        return $this;
    }

}