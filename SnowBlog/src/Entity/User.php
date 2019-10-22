<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *  fields= {"email"},
 * message= "L'email que vous avez indiqué est déja utilisé !"
 * )
 */
class User implements UserInterface  
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit contenir 8 caractères")
     */
    private $password;

    /**
     * * @Assert\EqualTo(propertyPath="password", message="Vos mots de passe sont différents")
     */
    public $confirm_password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FigureForum", mappedBy="user")
     */
    private $figureForums;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Figure", mappedBy="user")
     */
    private $figures;

    public function __construct()
    {
        $this->figureForums = new ArrayCollection();
        $this->figures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
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

    public function getSalt()
    {
        return null;
    }

    public function getRoles():array
    {
        $roles[] = 'ROLE_USER';

        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function eraseCredentials() {}

    /**
     * @return Collection|FigureForum[]
     */
    public function getFigureForums(): Collection
    {
        return $this->figureForums;
    }

    public function addFigureForum(FigureForum $figureForum): self
    {
        if (!$this->figureForums->contains($figureForum)) {
            $this->figureForums[] = $figureForum;
            $figureForum->setUser($this);
        }

        return $this;
    }

    public function removeFigureForum(FigureForum $figureForum): self
    {
        if ($this->figureForums->contains($figureForum)) {
            $this->figureForums->removeElement($figureForum);
            // set the owning side to null (unless already changed)
            if ($figureForum->getUser() === $this) {
                $figureForum->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Figure[]
     */
    public function getFigures(): Collection
    {
        return $this->figures;
    }

    public function addFigure(Figure $figure): self
    {
        if (!$this->figures->contains($figure)) {
            $this->figures[] = $figure;
            $figure->setUser($this);
        }

        return $this;
    }

    public function removeFigure(Figure $figure): self
    {
        if ($this->figures->contains($figure)) {
            $this->figures->removeElement($figure);
            // set the owning side to null (unless already changed)
            if ($figure->getUser() === $this) {
                $figure->setUser(null);
            }
        }

        return $this;
    }
}
