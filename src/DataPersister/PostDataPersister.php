<?php

namespace App\DataPersister;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class PostDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof Post;
    }

    public function persist($data, array $context = [])
    {
        if ($context['collection_operation_name'] ?? null === 'post') {
            $user = $this->tokenStorage->getToken()->getUser();
            $data->setUser($user);
        }

        if ($context['item_operation_name'] ?? null === 'put') {
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {

        try {
            if ($context['collection_operation_name'] != "delete_post_tag_relation" || $context['item_operation_name'] != "delete_post_like_relation"){
                $this->entityManager->remove($data);
                $this->entityManager->flush();
            }
        }
        catch (\Exception $exception) {
            return $exception;
        }
        return $data;
    }

}