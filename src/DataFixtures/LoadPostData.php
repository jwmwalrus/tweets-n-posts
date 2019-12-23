<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPostData extends Fixture implements DependentFixtureInterface
{
    public const MAX_POSTS = 5;

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        $post = new Post();
        $post->setCreatedat($faker->dateTimeThisYear);
        $post->setTitle($faker->sentence);
        $post->setContent($faker->text);
        $author = $this->getReference('test-user');
        $post->setAuthor($author);
        $manager->persist($post);
        $manager->flush();

        $this->addReference('test-post', $post);


        for ($i = 1; $i <= LoadUserData::NUSERS; ++$i) {
            $author = $this->getReference("seed-user-{$i}");

            $nposts = $faker->numberBetween(1, self::MAX_POSTS);
            for ($j = 0; $j < $nposts; ++$j) {
                $post = new Post();
                $post->setCreatedat($faker->dateTimeThisYear);
                $post->setTitle($faker->sentence);
                $post->setContent($faker->text);
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
