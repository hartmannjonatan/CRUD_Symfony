<?php

namespace App\Entity;

use DateTime;
use DateTimeZone;
use App\Repository\PostagemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[UniqueConstraint(name: "slug_unique", columns: ["slug"])]
#[ORM\Entity(repositoryClass: PostagemRepository::class)]
class Postagem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    public $titulo;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $descricao;

    #[ORM\Column(type: 'string', length: 255)]
    private $tag;

    #[ORM\Column(type: 'text')]
    private $conteudo;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'string', length: 30)]
    private $slug;

    #[ORM\ManyToOne(targetEntity: Categoria::class, inversedBy: 'postagens')]
    #[ORM\JoinColumn(nullable: false)]
    private $categoriaId;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $author;

    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'postagens')]
    private $tags;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $slug_text;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(string $tag): self
    {

        $this->tag = $tag;

        return $this;
    }

    public function getConteudo(): ?string
    {
        return $this->conteudo;
    }

    public function setConteudo(string $conteudo): self
    {
        $this->conteudo = $conteudo;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): self
    {
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $this->createdAt = new DateTime('now', $timezone);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        if(!$slug){
            $slug = $this->titulo;
        }

        $this->setSlugText($slug);

        $slug = str_replace([' ', '/'], ['_', '-'], $slug);

        $this->slug = $slug;

        return $this;
    }

    public function getCategoriaId(): ?Categoria
    {
        return $this->categoriaId;
    }

    public function setCategoriaId(?Categoria $categoriaId): self
    {
        $this->categoriaId = $categoriaId;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author = 'Admin'): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addPostagem($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removePostagem($this);
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): self
    {
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $this->updatedAt = new DateTime('now', $timezone);

        return $this;
    }

    public function getSlugText(): ?string
    {
        return $this->slug_text;
    }

    public function setSlugText(?string $slug_text): self
    {
        $this->slug_text = $slug_text;

        return $this;
    }

}
