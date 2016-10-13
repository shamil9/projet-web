<?php


namespace AppBundle\Controller\Member;


use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class MemberController extends BaseController
{
    /**
     * Met à jour les données d'utilisateur
     *
     * @Route("/editer", name="user_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $user = $this->getUser();

        $user->setUsername($request->request->get('username'));
        $this->em()->flush();

        return $this->redirectToRoute('user_profile');
    }
}
