<?php namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class CreateProUsers implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_BE');
        
        for ($i = 0; $i < 10; $i++) {
            $proUser = new User();
            $proUser->setUsername($faker->userName);
            $proUser->setEmail($faker->email);
            $proUser->setPassword($faker->password());
            $proUser->setDescription($faker->paragraph(3));
            $proUser->setCity($faker->city);
            $proUser->setName($faker->name);
            $proUser->setPhone($faker->phoneNumber);
            $proUser->setStreet($faker->streetName);
            $proUser->setTva($faker->vat);
            $proUser->setWebsite($faker->url);
            $proUser->setZip($faker->postcode);

            $manager->persist($proUser);
            $manager->flush();
        }
    }
}
