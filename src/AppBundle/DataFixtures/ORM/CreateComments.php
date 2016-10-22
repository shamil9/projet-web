<?php namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Comment;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Illuminate\Support\Collection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreateComments extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var $container ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_BE');
        $em = $this->container->get('doctrine');
        $proMembers = new Collection($em->getRepository('AppBundle:ProMember')->findAll());
        $members = new Collection($em->getRepository('AppBundle:Member')->findAll());

        for ($i = 0; $i < 5; $i++) {
            $comment = new Comment();
            $comment->setComment($faker->paragraph(3));
            $comment->setMember($members->random());
            $comment->setProMember($proMembers->random());
            $comment->setRating($faker->numberBetween(0, 5));

            $manager->persist($comment);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
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
