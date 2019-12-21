<?php

namespace App\DataFixtures;

use App\Entity\Post;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPostData extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $post = new Post();
        $createdAt = new DateTime();
        $post->setCreatedat($createdAt);
        $post->setTitle('Test title');
        $post->setContent('Test content');
        $author = $this->getReference('test-user');
        $post->setAuthor($author);
        $manager->persist($post);
        $manager->flush();

        $this->addReference('test-post', $post);


        for ($i = 1; $i <= 5; ++$i) {
            for ($j = 6 - $i; $j >= 1; --$j) {
                $post = new Post();
                $createdAt = new DateTime();
                $post->setCreatedat($createdAt);
                $post->setTitle("Title {$j} for user {$i}");
                $post->setContent("Content {$j} for user {$i}");
                $author = $this->getReference("seed-user-{$i}");
                $post->setAuthor($author);
                $manager->persist($post);
                $manager->flush();

                $this->addReference("seed-post-{$i}-{$j}", $post);
            }
        }
    }

    public function getDependencies()
    {
        return [
            LoadUserData::class,
        ];
    }
}
