<?php


namespace AppBundle\Managers;

use AppBundle\Entity\Favorite;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

class FavoritesManager
{
    private $em;

    /**
     * FavoritesManager constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Enregistre un prestataire favoris
     *
     * @param integer $member
     * @param integer $proMember
     * @return string
     * @throws \Exception
     */
    public function addFavoriteProMember($member, $proMember)
    {
        $favorite = new Favorite();
        if (!$member) {
            throw new \Exception('Operation non permise');
        }

        $favorite->setMember($member);
        $favorite->setProMember($proMember);
        $this->em->persist($favorite);
        $this->em->flush();

        return new Response('yay');
    }

    /**
     * Suppression de la liste de favoris
     *
     * @param integer $member
     * @param integer $proMember
     * @return Response
     */
    public function removeFavorite($member, $proMember)
    {
        $repo = $this->em->getRepository('AppBundle:Favorite');

        //Rechercher l'entrée
        $favorite = $repo->findOneBy([
            'proMember' => $proMember,
            'member' => $member,
        ]);

        $this->em->remove($favorite);
        $this->em->flush();

        return new Response('nay');
    }
}
