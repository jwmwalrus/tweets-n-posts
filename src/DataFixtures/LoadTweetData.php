<?php

namespace App\DataFixtures;

use App\Entity\Tweet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTweetData extends Fixture implements DependentFixtureInterface
{
    public const MAX_POSTS = 5;

    public function load(ObjectManager $manager)
    {
        $tweet = new Tweet();
        $tweet->setItemid('Item ID');
        $tweet->setRaw('Raw');
        $tweet->setPlain('Plain');
        $tweet->setTimestamp(time());
        $owner = $this->getReference('test-user');
        $tweet->setOwner($owner);
        $manager->persist($tweet);
        $manager->flush();

        $this->addReference('test-tweet', $tweet);
    }

    public function getDependencies()
    {
        return [
            LoadUserData::class,
        ];
    }
}
