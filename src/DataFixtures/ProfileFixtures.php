<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ProfileFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');


        for ($nbProfiles = 1; $nbProfiles < 29; $nbProfiles++) {
            $profile = new Profile();
            $profile->setUser($this->getReference('user_' . $nbProfiles));
            $profile->setDescription($faker->text(150));
            $profile->setTitle($faker->text(30));
            $profile->setBirthday($faker->dateTime);
            $profile->addView($this->getReference('user_' . $faker->numberBetween(1,29)));
            $profile->addGame($this->getReference('game_' . $faker->numberBetween(1,29)));

            $manager->persist($profile);

            $this->addReference('profile_' . $nbProfiles, $profile);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}


