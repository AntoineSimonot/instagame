<?php

namespace App\DataPersister;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class TagDataPersister implements ContextAwareDataPersisterInterface
{

    private EntityManagerInterface $entityManager;
    private TokenStorageInterface $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Tag;
    }

    public function persist($data, array $context = [])
    {
        if ($context['collection_operation_name'] ?? null === 'post') {
            $sanitized_name = $this->sanitizer($data);
            $data->setName($sanitized_name);
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }

    public function sanitizer($data): string|null
    {
        $trimName = trim(strtolower($data->getName()));
        return preg_replace('/\s+/', '_', $trimName); // replace spaces by "_"
    }

}