<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Article", mappedBy="tags")
     */
    private $articles;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Realization", mappedBy="tags")
     */
    private $realizations;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->realizations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getname();
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

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
        }

        return $this;
    }

    /**
     * @return Collection|Realization[]
     */
    public function getRealizations(): Collection
    {
        return $this->realizations;
    }

    public function addRealization(Realization $realization): self
    {
        if (!$this->realizations->contains($realization)) {
            $this->realizations[] = $realization;
            $realization->addTag($this);
        }

        return $this;
    }

    public function removeRealization(Realization $realization): self
    {
        if ($this->realizations->contains($realization)) {
            $this->realizations->removeElement($realization);
            $realization->removeTag($this);
        }

        return $this;
    }
}
