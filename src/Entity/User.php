<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $nom = 'John';

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TodoList::class)]
    private Collection $todoLists;

    #[ORM\OneToMany(mappedBy: 'commentUser', targetEntity: TodoList::class)]
    private Collection $todolistComments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ViewPost::class)]
    private Collection $viewPosts;

    public function __construct()
    {
        $this->todoLists = new ArrayCollection();
        $this->todolistComments = new ArrayCollection();
        $this->viewPosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, TodoList>
     */
    public function getTodoLists(): Collection
    {
        return $this->todoLists;
    }

    public function addTodoList(TodoList $todoList): self
    {
        if (!$this->todoLists->contains($todoList)) {
            $this->todoLists->add($todoList);
            $todoList->setUser($this);
        }

        return $this;
    }

    public function removeTodoList(TodoList $todoList): self
    {
        if ($this->todoLists->removeElement($todoList)) {
            // set the owning side to null (unless already changed)
            if ($todoList->getUser() === $this) {
                $todoList->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TodoList>
     */
    public function getTodolistComments(): Collection
    {
        return $this->todolistComments;
    }

    public function addTodolistComment(TodoList $todolistComment): self
    {
        if (!$this->todolistComments->contains($todolistComment)) {
            $this->todolistComments->add($todolistComment);
            $todolistComment->setCommentUser($this);
        }

        return $this;
    }

    public function removeTodolistComment(TodoList $todolistComment): self
    {
        if ($this->todolistComments->removeElement($todolistComment)) {
            // set the owning side to null (unless already changed)
            if ($todolistComment->getCommentUser() === $this) {
                $todolistComment->setCommentUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ViewPost>
     */
    public function getViewPosts(): Collection
    {
        return $this->viewPosts;
    }

    public function addViewPost(ViewPost $viewPost): self
    {
        if (!$this->viewPosts->contains($viewPost)) {
            $this->viewPosts->add($viewPost);
            $viewPost->setUser($this);
        }

        return $this;
    }

    public function removeViewPost(ViewPost $viewPost): self
    {
        if ($this->viewPosts->removeElement($viewPost)) {
            // set the owning side to null (unless already changed)
            if ($viewPost->getUser() === $this) {
                $viewPost->setUser(null);
            }
        }

        return $this;
    }
}
