<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Avatar
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Entity\AvatarRepository")
 */
class Avatar
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string" , nullable=true)
     *
     * @Assert\NotBlank(message="Ajoutez une image")
     * @Assert\File(
     *      maxSize = "500k",
     *      mimeTypes={ "image/jpeg" , "image/png" },
     *      mimeTypesMessage = "Veuillez inserer une image valide (.jpeg ou .png)"
     * )
     */
    private $avatarImg;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvatarImg()
    {
        return $this->avatarImg;
    }

    public function setAvatarImg($avatarImg)
    {
        $this->avatarImg = $avatarImg;

        return $this;
    }
}