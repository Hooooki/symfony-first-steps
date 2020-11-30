<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{

    private $randomCategory = [

        'Sport',
        'Musical',
        'Games'

    ];

    public function load(ObjectManager $manager) : void
    {

        $faker = Factory::create();

        for($i = 0; $i < 3; $i++) {

            $category = new Category();

            $category->setName($faker->randomElement($this->randomCategory));
            $category->setDescription($faker->sentence(36));

            $manager->persist($category);

        }

        $manager->flush();
    }
}
