<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ListsRepository")
 */
class Lists
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="lists")
     */
    private $author;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $share;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $Movies = [];

    public function __construct()
    {
        $this->author = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
       $this->author = $author;

        return $this;
    }

    public function getShare(): ?bool
    {
        return $this->share;
    }

    public function setShare(?bool $share): self
    {
        $this->share = $share;

        return $this;
    }

    public function getMovies(): ?array
    {
        return $this->Movies;
    }

    public function setMovies(?array $Movies): self
    {
        $this->Movies = $Movies;

        return $this;
    }
}
