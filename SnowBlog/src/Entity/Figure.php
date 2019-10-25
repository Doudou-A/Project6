<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FigureRepository")
 */
class Figure
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=5, max=255, minMessage="Le champ doit contenir minimum 5 caractères")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, max=255)
     */
    private $summary;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=10)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateLastUpdate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="figures")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FigureForum", mappedBy="figure")
     * @ORM\JoinColumn(name="figure_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $figureForums;

    public function __construct()
    {
        $this->figureForums = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getDateLastUpdate(): ?\DateTimeInterface
    {
        return $this->dateLastUpdate;
    }

    public function setDateLastUpdate(?\DateTimeInterface $dateLastUpdate): self
    {
        $this->dateLastUpdate = $dateLastUpdate;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

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
            $figureForum->setFigure($this);
        }

        return $this;
    }

    public function removeFigureForum(FigureForum $figureForum): self
    {
        if ($this->figureForums->contains($figureForum)) {
            $this->figureForums->removeElement($figureForum);
            // set the owning side to null (unless already changed)
            if ($figureForum->getFigure() === $this) {
                $figureForum->setFigure(null);
            }
        }

        return $this;
    }
}
