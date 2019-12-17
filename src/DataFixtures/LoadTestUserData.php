<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class LoadTestUserData extends Fixture
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
    }
}
