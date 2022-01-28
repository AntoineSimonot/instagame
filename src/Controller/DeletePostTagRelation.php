<?php
// api/src/Controller/CreateMediaObjectAction.php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class DeletePostTagRelation extends AbstractController
{

    public function __invoke(TagRepository $tagRepository, Request $request, PostRepository $postRepository, EntityManagerInterface $entityManager): Post
    {
        $post = $postRepository->find($request->get("post_id"));
        $tag = $tagRepository->find($request->get("tag_id"));
        $post->removeTag($tag);
        $entityManager->persist($post);
        $entityManager->flush();
        return $post;
    }
}