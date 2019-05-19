<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface,\Serializable
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isActive = true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="writer")
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Block", mappedBy="user")
     */
    private $blocks;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->blocks = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getusername();
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     * 
     *      public function getRoles()
     *      {
     *          return array('ROLE_USER');
     *      }
     * 
     * Alternatively, the roles might be stored on a ''roles'' property,
     * and populated in any number of different ways hen the user object
     * is created
     * 
     * @return (Role|string)[] The user roles
     */

    public function getRoles()
    {
        if (empty($this->roles)) {
            return ['ROLE_ADMIN'];
        }
        return $this->roles;
    }

    function addRole($role) {
        $this->roles[] = $role;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     * 
     * This can return null if th password was not encoded using a salt.
     * 
     * @return string|null The salt
     */
    
    public function getSalt()
    {
        return null;
    }

    /**
     * Remove sensitive data from the user.
     * 
     * This can return null if th password was not encoded using a salt.
     * 
     */
    public function eraseCredentials()
    {
        
    }

    /**
     * String representation of object
     * 
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    /**
     * Construct the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized, ['allowed_classes' => false]);
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
            $article->setWriter($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getWriter() === $this) {
                $article->setWriter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Block[]
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    public function addBlock(Block $block): self
    {
        if (!$this->blocks->contains($block)) {
            $this->blocks[] = $block;
            $block->setUser($this);
        }

        return $this;
    }

    public function removeBlock(Block $block): self
    {
        if ($this->blocks->contains($block)) {
            $this->blocks->removeElement($block);
            // set the owning side to null (unless already changed)
            if ($block->getUser() === $this) {
                $block->setUser(null);
            }
        }

        return $this;
    }

    
}
