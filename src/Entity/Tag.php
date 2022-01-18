<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $nametag;

    #[ORM\ManyToMany(targetEntity: Postagem::class, inversedBy: 'tags')]
    private $postagens;

    public function __construct()
    {
        $this->postagens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNametag(): ?string
    {
        return $this->nametag;
    }

    public function setNametag(?string $nametag): self
    {
        $this->nametag = $nametag;

        return $this;
    }

    /**
     * @return Collection|Postagem[]
     */
    public function getPostagens(): Collection
    {
        return $this->postagens;
    }

    public function addPostagem(Postagem $postagem): self
    {
        if (!$this->postagens->contains($postagem)) {
            $this->postagens[] = $postagem;
        }

        return $this;
    }

    public function removePostagem(Postagem $postagem): self
    {
        $this->postagens->removeElement($postagem);

        return $this;
    }
}
