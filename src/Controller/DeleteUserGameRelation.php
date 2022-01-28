<?php
// api/src/Controller/CreateMediaObjectAction.php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsController]
final class DeleteUserGameRelation extends AbstractController
{

    public function __invoke(Request $request, TokenStorageInterface $tokenStorage, GameRepository $gameRepository, EntityManagerInterface $entityManager): User
    {
        $user = $tokenStorage->getToken()->getUser();
        $game = $gameRepository->find($request->get("id"));
        $game->removeUser($user);
        $entityManager->persist($game);
        $entityManager->flush();
        return $user;
    }
}