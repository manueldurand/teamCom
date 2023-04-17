<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TodoListRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
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
    private ?string $reponse = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $dateReponse = null;

    #[ORM\ManyToOne(inversedBy: 'todoLists')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'todolistComments')]
    private ?user $commentUser = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: ViewPost::class)]
    private Collection $views;

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
        $this->views = new ArrayCollection();

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCommentUser(): ?user
    {
        return $this->commentUser;
    }

    public function setCommentUser(?user $commentUser): self
    {
        $this->commentUser = $commentUser;

        return $this;
    }

    /**
     * @return Collection<int, ViewPost>
     */
    public function getViews(): Collection
    {
        return $this->views;
    }

    public function addView(ViewPost $view): self
    {
        if (!$this->views->contains($view)) {
            $this->views->add($view);
            $view->setPost($this);
        }

        return $this;
    }

    public function removeView(ViewPost $view): self
    {
        if ($this->views->removeElement($view)) {
            // set the owning side to null (unless already changed)
            if ($view->getPost() === $this) {
                $view->setPost(null);
            }
        }

        return $this;
    }
    /**
     * perlet de savoir si un todo a été vu par un utilisateur
     * @return boolean
     * @param User $user
     */
    public function isViewedByUser(User $user): bool {
        foreach($this->views as $view){
            if($view->getUser() === $user) return true;
        }
        return false;
    }

}
