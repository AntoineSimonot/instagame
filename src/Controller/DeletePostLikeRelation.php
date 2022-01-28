<?php
// api/src/Controller/CreateMediaObjectAction.php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsController]
final class DeletePostLikeRelation extends AbstractController
{

    public function __invoke(Request $request, TokenStorageInterface $tokenStorage, PostRepository $postRepository, EntityManagerInterface $entityManager): Post
    {
        $user = $tokenStorage->getToken()->getUser();
        $post = $postRepository->find($request->get("id"));
        $post->removeLike($user);
        $entityManager->persist($post);
        $entityManager->flush();
        return $post;
    }
}