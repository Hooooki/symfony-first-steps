<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++)
        {
            $article = new Article();

            $sentence = $faker->sentence(4);
            $title = substr($sentence, 0, strlen($sentence) - 1);
            $article->setTitle($title)
                ->setSlug((new Slugify())->slugify($title))
                ->setAuthor($faker->name())
                ->setContent($faker->text(3000))
                ->setCreatedAt($faker->dateTimeThisYear());

            $manager->persist($article);
        }

        $manager->flush();
    }
}
