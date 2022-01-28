<?php
// api/src/Controller/CreateMediaObjectAction.php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Profile;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsController]
final class AddView extends AbstractController
{

    public function __invoke(UserRepository $userRepository, ProfileRepository $profile, Request $request, TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager): Profile
    {
        $profile = $tokenStorage->getToken()->getUser()->getProfile();
        $user = $userRepository->find($request->get("id"));
        $profile->addView($user);
        $entityManager->persist($profile);
        $entityManager->flush();
        return $profile;
    }
}