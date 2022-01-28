<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\DeletePostTagRelation;
use App\Controller\DeleteUserGameRelation;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert; // Symfony's built-in constraints
#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['method' => 'get'],
        'post' => ['method' => 'post'],
        'delete_profile_tag_relation' => [
            'method' => 'DELETE',
            'path' => '/profiles/{tag_id}/tags/{post_id}',
            'controller' => DeletePostTagRelation::class,
        ]
    ],
    itemOperations: [
        'get' => ['method' => 'get'],
        'delete' => ['method' => 'delete'],
        'put' => [
            'normalization_context' => ['groups' => ['tag:put']],
        ],
    ],
    denormalizationContext: ['groups' => ['tag:write']],
    mercure: true,
    normalizationContext: ['groups' => ['tag:read']],
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["tag:read", "tag:write", "tag:put", "post:read"])]
    #[Assert\NotBlank]
    private $name;

    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'tags')]
    #[Groups(["tag:read", "tag:write", "tag:put",])]
    private $post;

    public function __construct()
    {
        $this->post = new ArrayCollection();
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

    /**
     * @return Collection|Post[]
     */
    public function getPost(): Collection
    {
        return $this->post;
    }

    public function addPost(Post $post): self
    {
        if (!$this->post->contains($post)) {
            $this->post[] = $post;
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        $this->post->removeElement($post);

        return $this;
    }
}
