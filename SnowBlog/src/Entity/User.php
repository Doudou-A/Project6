<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $adminAccess;

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

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

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

    public function getAdminAccess(): ?bool
    {
        return $this->adminAccess;
    }

    public function setAdminAccess(bool $adminAccess): self
    {
        $this->adminAccess = $adminAccess;

        return $this;
    }

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
