<?php namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Member;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreateMembers extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        $customUser = new Member();
        $customUser->setIsActive(1);
        $customUser->setUsername('Membre');
        $customUser->setName('Membre');
        $customUser->setEmail('membre@simple.com');
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($customUser);
        $customUser->setPassword($encoder->encodePassword('membre', $customUser->getSalt()));
        $customUser->setRegistrationDate();
        $manager->persist($customUser);

        for ($i = 0; $i < 10; $i++) {
            $user = new Member();
            $user->setName($faker->name);
            $user->setIsActive(0);
            $user->setUsername($faker->userName);
            $user->setEmail($faker->email);
            $user->setPassword($faker->password());
            $user->setRegistrationDate();
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
