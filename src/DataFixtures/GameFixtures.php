<?php

namespace App\DataFixtures;

use App\Entity\Game;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class GameFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');


        for ($nbGames = 1; $nbGames < 30; $nbGames++) {
            $game = new Game();
            $game->setName($faker->text(50));
            $manager->persist($game);

            $this->addReference('game_' . $nbGames, $game);
        }

        $manager->flush();
    }

}
