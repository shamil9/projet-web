<?php namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Workshop;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Illuminate\Support\Collection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreateWorkshops extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        for ($i = 0; $i < 50; $i++) {
            $workshop = new Workshop();
            $workshop->setName(array_reduce($faker->words(2), function($carry, $item) {
                $carry = $carry . ' ' . $item;
                return $carry;
            }));
            $workshop->setDescription($faker->paragraph(3));
            $workshop->setPrice($faker->numberBetween(10, 100));
            $workshop->setStart($faker->dateTime);
            $workshop->setEnd($faker->dateTimeThisYear);
            $workshop->setDisplayFrom($faker->dateTime);
            $workshop->setDisplayUntil($faker->dateTimeThisYear);
            $workshop->setUser($users->random());

            $manager->persist($workshop);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
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
