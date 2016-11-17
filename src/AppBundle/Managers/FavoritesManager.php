<?php


namespace AppBundle\Managers;

use AppBundle\Entity\Favorite;
use AppBundle\Entity\Member;
use AppBundle\Entity\ProMember;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @param $favorites
     * @param $id
     * @throws \Exception
     */
    public function checkIfExist($favorites, $id)
    {
        /** @var Favorite $favorite */
        foreach ($favorites->toArray() as $favorite) {
            if ($favorite->getProMember()->getId() === $id) {
                throw new \Exception('Utilisateur déjà dans les favoris');
            }
        }
    }

    /**
     * Enregistre un prestataire favoris
     *
     * @param Member $member
     * @param ProMember $proMember
     * @return string
     * @throws \Exception
     */
    public function addFavoriteProMember(Member $member, ProMember $proMember)
    {
        $this->checkIfExist($member->getFavorites(), $proMember->getId());

        $favorite = new Favorite();
        $favorite->setMember($member);
        $favorite->setProMember($proMember);
        $this->em->persist($favorite);
        $this->em->flush();
    }

    /**
     * Suppression de la liste de favoris
     *
     * @param Member $member
     * @param ProMember $proMember
     * @return Response
     * @throws \Exception
     */
    public function removeFavorite(Member $member, ProMember $proMember)
    {
        if (!$member) {
            throw new \Exception('Operation non permise');
        }

        $repo = $this->em->getRepository('AppBundle:Favorite');
        //Rechercher l'entrée
        $favorite = $repo->findOneBy([
            'proMember' => $proMember,
            'member' => $member,
        ]);

        $this->em->remove($favorite);
        $this->em->flush();
    }
}
