<?php

namespace App\Entity;

use App\Repository\CategoriasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Post;

/**
 * @ORM\Entity(repositoryClass=CategoriasRepository::class)
 */
class Categorias
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="cat")
     */
    private $postagem;

    public function __construct()
    {
        $this->postagem = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPostagem(): Collection
    {
        return $this->postagem;
    }

    public function addPostagem(Post $postagem): self
    {
        if (!$this->postagem->contains($postagem)) {
            $this->postagem[] = $postagem;
            $postagem->setCat($this);
        }

        return $this;
    }

    public function removePostagem(Post $postagem): self
    {
        if ($this->postagem->removeElement($postagem)) {
            // set the owning side to null (unless already changed)
            if ($postagem->getCat() === $this) {
                $postagem->setCat(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nome;
    }
}
