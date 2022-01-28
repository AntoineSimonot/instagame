<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\AddView;
use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['method' => 'get'],
        'post' => ['method' => 'post'],
        'add_view' => [
            'method' => 'POST',
            'path' => '/profiles/add_view/{id}',
            'controller' => AddView::class,
        ],
    ],
    itemOperations: [
        'get' => [
            'method' => 'get'
        ],
        'put' => [
            'normalization_context' => ['groups' => ['profile:put']],
        ],
        'delete' => ['method' => 'delete'],
    ],
    denormalizationContext: ['groups' => ['profile:write']],
    normalizationContext: ['groups' => ['profile:read']],
)]
#[UniqueEntity(
    fields: ['user', 'user'],
    message: 'This user is already in use on that profile.',
    errorPath: 'user',
)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["profile:read", "profile:write", "profile:put", "user:read"])]
    private ?string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(["profile:read", "profile:write", "profile:put", "user:read"])]
    private ?string $description;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(["profile:read", "profile:write", "profile:put", "user:read"])]
    #[Assert\Type(\DateTimeInterface::class)]
    private ?\DateTimeInterface $birthday;

    #[ORM\OneToOne(inversedBy: 'profile', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[Groups(["profile:read", "profile:write"])]
    #[Assert\NotBlank]
    private ?User $user;

    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'profile')]
    #[Groups(["profile:read", "profile:write", "user:read"])]
    private $games;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'views')]
    #[Groups(["profile:read"])]
    private $views;

    #[Pure] public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->views = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

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


    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->addProfile($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            $game->removeProfile($this);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getViews(): Collection
    {
        return $this->views;
    }

    public function addView(User $view): self
    {
        if (!$this->views->contains($view)) {
            $this->views[] = $view;
        }

        return $this;
    }

    public function removeView(User $view): self
    {
        $this->views->removeElement($view);

        return $this;
    }

}
