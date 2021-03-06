<?php


namespace AppBundle\Controller\Member;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\ProMember;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FavoritesController extends BaseController
{
    /**
     * @Route("/membres/favoris/ajouter/{proMember}", name="members_add_favorite")
     * @param ProMember $proMember
     *
     * @return string
     * @throws \Exception
     */
    public function addFavoriteAction(ProMember $proMember)
    {
        $this->userCheck();

        try {
            $favoriteManager = $this->get('app.favorites_manager');
            $favoriteManager->addFavoriteProMember($this->getUser(), $proMember);

            return JsonResponse::create(null, 200);
        } catch (\Exception $e) {
            return JsonResponse::create($e, 500);
        }
    }

    /**
     * @Route("/membres/favoris/supprimer/{proMember}", name="members_remove_favorite")
     * @param ProMember $proMember
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param Request $request
     */
    public function removeFavoriteAction(ProMember $proMember)
    {
        $this->userCheck();

        try {
            $favoriteManager = $this->get('app.favorites_manager');

            $favoriteManager->removeFavorite($this->getUser(), $proMember);

            return JsonResponse::create(null, 200);
        } catch (\Exception $e) {
            return JsonResponse::create($e, 500);
        }
    }
}
