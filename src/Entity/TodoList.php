<?php

namespace App\Entity;

use App\Repository\TodoListRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TodoListRepository::class)]
class TodoList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $created = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $auteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reponse = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $dateReponse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $auteurReponse = null;

    #[ORM\Column]
    private ?int $idAuteur = null;

    #[ORM\Column(nullable: true)]
    private ?int $idAuteurReponse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->dateReponse = new \DateTime();
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(?string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(?string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->dateReponse;
    }

    public function setDateReponse(?\DateTimeInterface $dateReponse): self
    {
        $this->dateReponse = $dateReponse;

        return $this;
    }

    public function getAuteurReponse(): ?string
    {
        return $this->auteurReponse;
    }

    public function setAuteurReponse(?string $auteurReponse): self
    {
        $this->auteurReponse = $auteurReponse;

        return $this;
    }

    public function getIdAuteur(): ?int
    {
        return $this->idAuteur;
    }

    public function setIdAuteur(int $idAuteur): self
    {
        $this->idAuteur = $idAuteur;

        return $this;
    }

    public function getIdAuteurReponse(): ?int
    {
        return $this->idAuteurReponse;
    }

    public function setIdAuteurReponse(?int $idAuteurReponse): self
    {
        $this->idAuteurReponse = $idAuteurReponse;

        return $this;
    }
}
