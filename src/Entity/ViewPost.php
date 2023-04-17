<?php

namespace App\Entity;

use App\Repository\ViewPostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ViewPostRepository::class)]
class ViewPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'views')]
    private ?TodoList $post = null;

    #[ORM\ManyToOne(inversedBy: 'viewPosts')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?TodoList
    {
        return $this->post;
    }

    public function setPost(?TodoList $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
