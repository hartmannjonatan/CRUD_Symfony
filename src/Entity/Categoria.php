<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use DateTime;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueConstraint(name: "slug_unique", columns: ["slug"])]
#[ORM\Entity(repositoryClass: CategoriaRepository::class)]
class Categoria extends CategoriaRepository
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 30)]
    private $name;

    #[ORM\Column(type: 'string', length: 50)]
    private $slug;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     */
    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: Postagem::class, inversedBy: 'categoriaID')]
    private $postagem;

    /**
     * @var \DateTime $updatedAt
     *
     * @Gedmo\Timestampable(on="update")
    */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updatedAt;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $slug_text;

    public function __construct()
    {
        $this->postagens = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->setSlugText($slug);

        $slug = str_replace([' ', '/'], ['_', '-'], $slug);

        $this->slug = $slug;

        return $this;
    }

    public function setCreatedAt(): self
    {
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $this->createdAt = new DateTime('now', $timezone);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getPostagem(): ?Postagem
    {
        return $this->postagem;
    }

    public function setPostagem(?Postagem $postagem): self
    {
        $this->postagem = $postagem;

        return $this;
    }

    /**
     * @return Collection|Postagem[]
     */
    public function getPostagens(): Collection
    {
        return $this->postagens;
    }

    public function addPostagens(Postagem $postagens): self
    {
        if (!$this->postagens->contains($postagens)) {
            $this->postagens[] = $postagens;
            $postagens->setCategoriaId($this);
        }

        return $this;
    }

    public function removePostagens(Postagem $postagens): self
    {
        if ($this->postagens->removeElement($postagens)) {
            // set the owning side to null (unless already changed)
            if ($postagens->getCategoriaId() === $this) {
                $postagens->setCategoriaId(null);
            }
        }

        return $this;
    }

    public function setUpdatedAt(): self
    {
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $this->updatedAt = new DateTime('now', $timezone);

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
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
