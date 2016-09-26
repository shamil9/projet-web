<?php namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Category;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class CreateCategories implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_BE');
        
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName(array_reduce($faker->words(2), function($carry, $item) {
                $carry = $carry . ' ' . $item;
                return $carry; 
            }));
            $category->setPromoted($faker->boolean);
            $category->setDescription($faker->paragraph(3));

            $manager->persist($category);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 1;
    }
}
