<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');


        for ($nbPosts = 1; $nbPosts < 29; $nbPosts++) {
            $post = new Post();
            $post->setContent($faker->text(50));
            $post->setUser($this->getReference('user_' . $faker->numberBetween(1,29)));
            $post->addTag($this->getReference('tag_' . $faker->numberBetween(1,29)));
            $post->addLike($this->getReference('user_' . $faker->numberBetween(1,29)));

            $manager->persist($post);

            $this->addReference('post_' . $nbPosts, $post);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TagFixtures::class,
            UserFixtures::class
        ];
    }
}


