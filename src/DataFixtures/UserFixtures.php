<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function hashPassword(User $user): string
    {
        return $this->passwordHasher->hashPassword($user, $user->getPassword());
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($users = 1; $users < 30; $users++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setPassword($this->passwordHasher->hashPassword($user, $faker->password()));
            $user->setPseudo($faker->name);
            $manager->persist($user);

            $this->addReference('user_' . $users, $user);

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TagFixtures::class
        ];
    }
}
