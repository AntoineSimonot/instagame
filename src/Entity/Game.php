<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\DeleteUserGameRelation;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['method' => 'get'],
        'post' => ['method' => 'post'],
    ],
    itemOperations: [
        'delete_user_game_relation' => [
            'method' => 'DELETE',
            'path' => '/user_game_relation/{id}',
            'controller' => DeleteUserGameRelation::class,
        ],
        'get' => ['method' => 'get'],
        'delete' => ['method' => 'delete'],
        'put' => [
            'normalization_context' => ['groups' => ['game:put']],
        ],
    ],
    denormalizationContext: ['groups' => ['game:write']],
    normalizationContext: ['groups' => ['game:read']],
)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["game:read", "game:write", "game:put", "profile:read"])]
    private $name;

    #[ORM\ManyToMany(targetEntity: Profile::class, inversedBy: 'games')]
    #[Groups(["game:read", "game:write", "game:put"])]
    private $profile;

    public function __construct()
    {
        $this->profile = new ArrayCollection();
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
     * @return Collection|Profile[]
     */
    public function getProfile(): Collection
    {
        return $this->profile;
    }

    public function addProfile(Profile $profile): self
    {
        if (!$this->profile->contains($profile)) {
            $this->profile[] = $profile;
        }

        return $this;
    }

    public function removeProfile(Profile $profile): self
    {
        $this->profile->removeElement($profile);

        return $this;
    }
}
