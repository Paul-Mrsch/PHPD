<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $array = [
            [
                'name' => 'sport',
                'description' => 'sport description'
            ],
            [
                'name' => 'auto',
                'description' => 'aotu description'
            ],
            [
                'name' => 'cuisine',
                'description' => 'cuisine description'
            ],
        ];
        foreach ($array as $value) {
            $category = new Category;
            $category->setName($value['name']);
            $category->setDescription($value['description']);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
