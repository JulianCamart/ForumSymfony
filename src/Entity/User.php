<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username" , message="Le pseudo indiqué est déja utilisé")
 * @UniqueEntity("email" , message="L'email indiqué est déja utilisé")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     * @Assert\NotBlank(message="Ce champ doit etre rempli")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=8 , max=30, minMessage="Votre mot de passe doit faire minimum 8 caractères", maxMessage="Votre mot de passe doit faire maximum 30 caractères")
     * @Assert\NotBlank(message="Ce champ doit etre rempli")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Vos mots de passe doivent être identiques")
     * @Assert\NotBlank(message="Ce champ doit etre rempli")
     */
    public $ConfirmPassword;

    /**
     * @ORM\Column(type="string", length=250, unique=true)
     * @Assert\Email(message="L'email '{{ value }}' n'est pas valide.")
     * @Assert\NotBlank(message="Ce champ doit etre rempli")
     */
    private $email;

    /**
    * @ORM\Column(type="json")
    */
    private $roles = [];

    /**
     *  @ORM\OneToMany(targetEntity="App\Entity\ForumTable", mappedBy="ForumAuthor")
     */
    private $forums;

    /**
     *  @ORM\OneToMany(targetEntity="App\Entity\ThreadTable", mappedBy="ThreadAuthor")
     */
    private $threads;

    /**
     *  @ORM\OneToMany(targetEntity="App\Entity\PostTable", mappedBy="PostAuthor")
     */
    private $posts;

    /**
     *  @ORM\OneToMany(targetEntity="App\Entity\PrivateMessageTable", mappedBy="MessageAuthor")
     */
    private $privateMessagesAuthor;

    /**
     *  @ORM\OneToMany(targetEntity="App\Entity\PrivateMessageTable", mappedBy="MessageReceiver")
     */
    private $privateMessagesReceiver;


    /**
     * @ORM\Column(name="report", type="integer")
     */
    protected $report = 0;

    /**
     *  @ORM\OneToMany(targetEntity="App\Entity\ReportTable", mappedBy="reportUser", cascade={"remove"})
     */
    private $reports;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Avatar", cascade="remove")
     */
    private $avatar;

    /**
     * @ORM\Column(name="verif_email", type="boolean", options={"default":false})
     */
    private $verifEmail;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $memberSinceTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $passwordRequestedAt;

    /**
    * @var string
    *
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    private $token;

    public function __construct()
    {
        $this->posts = new ArrayCollection();

        $this->threads = new ArrayCollection();

        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    function setRole($role)
    {
        $this->roles = $role;
        return $this;
    }

    /**
     * Get avatar
     *
     * @return App\Entity\Avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set avatar
     *
     * @param App\Entity\Avatar $avatar
     * @return User
     */
    public function setAvatar(Avatar $avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {

    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([$this->id, $this->username, $this->password]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
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
            $ForumTable->setUser($this);
        }

        return $this;
    }

    public function removeForum(ForumTable $ForumTable): self
    {
        if ($this->forums->contains($ForumTable)) {
            $this->forums->removeElement($ForumTable);
            if ($ForumTable->getUser() === $this) {
                $ForumTable->setUser(null);
            }
        }
    }

    /**
     *  @return Collection|ThreadTable[]
     */
    public function getThreads(): Collection
    {
        return $this->threads;
    }

    public function addThread(ThreadTable $ThreadTable): self
    {
        if (!$this->threads->contains($ThreadTable)) {
            $this->threads[] = $ThreadTable;
            $ThreadTable->setUser($this);
        }

        return $this;
    }

    public function removeThread(ThreadTable $ThreadTable): self
    {
        if ($this->threads->contains($ThreadTable)) {
            $this->threads->removeElement($ThreadTable);
            if ($ThreadTable->getUser() === $this) {
                $ThreadTable->setUser(null);
            }
        }
    }

    /**
     *  @return Collection|PostTable[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(PostTable $PostTable): self
    {
        if (!$this->posts->contains($PostTable)) {
            $this->posts[] = $PostTable;
            $PostTable->setUser($this);
        }

        return $this;
    }

    public function removePost(PostTable $PostTable): self
    {
        if ($this->posts->contains($PostTable)) {
            $this->posts->removeElement($PostTable);
            if ($PostTable->getUser() === $this) {
                $PostTable->setUser(null);
            }
        }
    }


    /**
     *  @return Collection|PrivateMessageTable[]
     */
    public function getMessagesAuthor(): Collection
    {
        return $this->MessagesAuthor;
    }

    public function addMessagesAuthor(PrivateMessageTable $PrivateMessageTable): self
    {
        if (!$this->MessagesAuthor->contains($PrivateMessageTable)) {
            $this->MessagesAuthor[] = $PrivateMessageTable;
            $PrivateMessageTable->setUser($this);
        }

        return $this;
    }

    public function removeMessageAuthor(PrivateMessageTable $PrivateMessageTable): self
    {
        if ($this->MessagesAuthor->contains($PrivateMessageTable)) {
            $this->MessagesAuthor->removeElement($PrivateMessageTable);
            if ($PrivateMessageTable->getUser() === $this) {
                $PrivateMessageTable->setUser(null);
            }
        }
    }


    /**
     *  @return Collection|PrivateMessageTable[]
     */
    public function getMessagesReceiver(): Collection
    {
        return $this->MessagesReceiver;
    }

    public function addMessagesReceiver(PrivateMessageTable $PrivateMessageTable): self
    {
        if (!$this->MessagesReceiver->contains($PrivateMessageTable)) {
            $this->MessagesReceiver[] = $PrivateMessageTable;
            $PrivateMessageTable->setUser($this);
        }

        return $this;
    }

    public function removeMessageReceiver(PrivateMessageTable $PrivateMessageTable): self
    {
        if ($this->MessagesReceiver->contains($PrivateMessageTable)) {
            $this->MessagesReceiver->removeElement($PrivateMessageTable);
            if ($PrivateMessageTable->getUser() === $this) {
                $PrivateMessageTable->setUser(null);
            }
        }
    }

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

    public function getVerifEmail(): ?bool
    {
        return $this->verifEmail;
    }


    public function setVerifEmail($verifEmail): self
    {
        $this->verifEmail = $verifEmail;
        return $this;
    }

    public function getMemberSinceTime(): ?\DateTimeInterface
    {
        return $this->memberSinceTime;
    }

    public function setMemberSinceTime(\DateTimeInterface $memberSinceTime): self
    {
        $this->memberSinceTime = $memberSinceTime;
        return $this;
    }

    /*
     * Get passwordRequestedAt
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /*
     * Set passwordRequestedAt
     */
    public function setPasswordRequestedAt($passwordRequestedAt)
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
        return $this;
    }

    /*
     * Get token
     */
    public function getToken()
    {
        return $this->token;
    }

    /*
     * Set token
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }
}
