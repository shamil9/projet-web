<?php namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\ProMember;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Illuminate\Support\Collection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreateProUsers extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        
        $customUser = new ProMember();
        $customUser->setIsActive(1);
        $customUser->setUsername('ProMembre');
        $customUser->setEmail('membre@pro.com');
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($customUser);
        $customUser->setPassword($encoder->encodePassword('promembre', $customUser->getSalt()));
        $customUser->setDescription($faker->paragraph(3));
        $customUser->setCity('Liège');
        $customUser->setName('Pro Membre');
        $customUser->setPhone($faker->phoneNumber);
        $customUser->setStreet('Rue Natalis 22');
        $customUser->setTva($faker->vat);
        $customUser->setWebsite($faker->url);
        $customUser->setZip('4020');
        $customUser->setCategories($categories->random(3)->all());
        $customUser->setRegistrationDate();
        $manager->persist($customUser);
        
        for ($i = 0; $i < 10; $i++) {
            $proUser = new ProMember();
            $proUser->setIsActive(1);
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
            $proUser->setZip(4000);
            $proUser->setCategories($categories->random(3)->all());
            $proUser->setRegistrationDate();
            $manager->persist($proUser);
        }
        
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
