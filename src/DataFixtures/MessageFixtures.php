<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbMessages = 1; $nbMessages < 30; $nbMessages++) {
            $message = new Message();
            $message->setSender($this->getReference('user_' . $faker->numberBetween(1,29)));
            $message->setRecipient($this->getReference('user_' . $faker->numberBetween(1,29)));
            $message->setContent($faker->realText(150));
            $manager->persist($message);

            $this->addReference('message_' . $nbMessages, $message);

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
