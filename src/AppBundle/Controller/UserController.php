<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Member;
use AppBundle\Entity\User;
use AppBundle\Form\MemberEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{
    /**
     * Affiche la page du profil d'utilisateur selon son type
     *
     * @Route("/profil", name="user_profile")
     * @param Request $request
     * @var User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request)
    {
        //utilisateur courant
        $user = $this->getUser();

        if ($user instanceof Member) {
            $form = $this->createForm(MemberEditType::class);

            return $this->render('member/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        }

        return $this->render('pro_member/edit.html.twig', ['user' => $user]);
    }

    /**
     * Supprime un utilisateur
     *
     * @Route("/supprimer", name="user_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function destroyAction()
    {
        //utilisateur courant
        $user = $this->getUser();

        //suppression d'utilistateur
        $this->em()->remove($user);
        $this->em()->flush();

        return $this->redirectToRoute('homepage');
    }
}
