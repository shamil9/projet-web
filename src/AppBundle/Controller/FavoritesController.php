<?php


namespace AppBundle\Controller;


use AppBundle\Entity\ProMember;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FavoritesController extends BaseController
{
    /**
     * @Route("/membres/favoris/ajouter/{proMember}", name="members_add_favorite")
     * @param ProMember $proMember
     * @return string
     * @throws \Exception
     */
    public function addFavoriteAction(ProMember $proMember)
    {
        $favoriteManager = $this->get('app.favorites_manager');

        return $favoriteManager->addFavoriteProMember($this->getUser(), $proMember->getId());
    }

    /**
     * @Route("/membres/favoris/supprimer/{proMember}", name="members_remove_favorite")
     * @param ProMember $proMember
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeFavoriteAction(ProMember $proMember)
    {
        $favoriteManager = $this->get('app.favorites_manager');

        return $favoriteManager->removeFavorite($this->getUser(), $proMember->getId());
    }
}
