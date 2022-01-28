<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\DeletePostLikeRelation;
use App\Controller\DeletePostTagRelation;
use App\Controller\DeleteUserGameRelation;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['method' => 'get'],
        'post' => ['method' => 'post'],
        'delete_post_tag_relation' => [
            'method' => 'DELETE',
            'path' => '/posts/{post_id}/tags/{tag_id}',
            'controller' => DeletePostTagRelation::class,
        ],

    ],
    itemOperations: [
        'get' => ['method' => 'get'],
        'put' => [
            'normalization_context' => ['groups' => ['post:put']],
        ],
        'delete_post_like_relation' => [
            'method' => 'DELETE',
            'path' => '/posts/{id}/like',
            'controller' => DeletePostLikeRelation::class,
        ],
    ],
    denormalizationContext: ['groups' => ['post:write']],
    normalizationContext: ['groups' => ['post:read']],
)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    #[Groups(["post:read", "post:write", "post:put"])]
    private $content;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[Groups(["post:read", "post:write", "post:put"])]
    private $user;

    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'post')]
    #[Groups(["post:read", "post:write", "post:put"])]
    private $tags;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'likes')]
    #[Groups(["post:read", "post:write", "post:put"])]
    private $likes;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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
            $tag->addPost($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removePost($this);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(User $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
        }

        return $this;
    }

    public function removeLike(User $like): self
    {
        $this->likes->removeElement($like);

        return $this;
    }
}
