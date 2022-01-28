<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');


        for ($nbTags = 1; $nbTags < 30; $nbTags++) {
            $tag = new Tag();
            $tag->setName($faker->text(50));
            $manager->persist($tag);

            $this->addReference('tag_' . $nbTags, $tag);
        }

        $manager->flush();
    }

}
