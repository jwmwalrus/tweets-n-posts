<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class LoadUserData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('Name');
        $user->setUsername('testuser');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT, ['cost' => 14]));
        $user->setEmail('example@example.com');
        $user->setTwitterid('someid');
        $manager->persist($user);
        $manager->flush();

        $this->addReference('test-user', $user);

        for ($i = 1; $i <= 5; ++$i) {
            $user = new User();
            $user->setName("User {$i}");
            $user->setUsername("user{$i}");
            $user->setPassword(password_hash('jobsity', PASSWORD_BCRYPT, ['cost' => 14]));
            $user->setEmail("user{$i}@example.com");
            $user->setTwitterid("user{$i}");
            $manager->persist($user);
            $manager->flush();

            $this->addReference("seed-user-{$i}", $user);
        }
    }
}
