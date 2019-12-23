<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class LoadUserData extends Fixture
{
    public const NUSERS = 5;

    public const TWITTER_IDS = [
        'thestrokes',
        'arcadefire',
        'foofighters',
        'DefLeppart',
        'TeslaBand',
    ];

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        $user = new User();
        $user->setName('Test user');
        $user->setUsername('testuser');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT, ['cost' => 14]));
        $user->setEmail('example@example.com');
        $user->setTwitterid('thebeatles');
        $manager->persist($user);
        $manager->flush();

        $this->addReference('test-user', $user);

        for ($i = 1; $i <= self::NUSERS; ++$i) {
            $user = new User();
            $user->setName($faker->name);
            $user->setUsername("user{$i}");
            $user->setPassword(password_hash('jobsity', PASSWORD_BCRYPT, ['cost' => 14]));
            $user->setEmail($faker->email);
            $user->setTwitterid(self::TWITTER_IDS[$i - 1]);
            $manager->persist($user);
            $manager->flush();

            $this->addReference("seed-user-{$i}", $user);
        }
    }
}
