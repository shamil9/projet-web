<?php namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Illuminate\Support\Collection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreateProUsers implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_BE');
        $em = $this->container->get('doctrine');
        $categories = new Collection($em->getRepository('AppBundle:Category')->findAll());

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
            $proUser->setCategory($categories->random(3)->all());

            $manager->persist($proUser);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 2;
    }
}
