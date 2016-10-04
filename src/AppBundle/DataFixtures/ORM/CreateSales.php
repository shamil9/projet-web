<?php namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Sale;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Illuminate\Support\Collection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreateSales implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var $container ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_BE');
        $em = $this->container->get('doctrine');
        $users = new Collection($em->getRepository('AppBundle:ProMember')->findAll());

        for ($i = 0; $i < 5; $i++) {
            $sale = new Sale();
            $sale->setName(array_reduce($faker->words(2), function($carry, $item) {
                $carry = $carry . ' ' . $item;
                return $carry;
            }));
            $sale->setDescription($faker->paragraph(3));
            $sale->setStart($faker->dateTime);
            $sale->setEnd($faker->dateTimeThisYear);
            $sale->setUser($users->random());

            $manager->persist($sale);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 4;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
